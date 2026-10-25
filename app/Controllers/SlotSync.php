<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\SlotSync_Lib;

/*
 * CLI only. Run on the production server (TREEM API allows whitelisted IPs only).
 *   php public/index.php slotsync run                 all providers
 *   php public/index.php slotsync run 603             single provider (slot_prd.code)
 *   php public/index.php slotsync run --dry-run       report only, no DB change
 *   php public/index.php slotsync run --force         ignore the mass-hide guard
 *   php public/index.php slotsync run 603 --json mg_games.json --dry-run   use a saved /game-list response
 */
class SlotSync extends Controller
{
    protected $helpers = ['common_helper', 'curl_helper'];

    public function run($prdCode = '')
    {
        if(!is_cli()){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if($_ENV['app.slot'] != APP_SLOT_TREEM){
            $this->out("app.slot is not TREEM. Nothing to do.");
            return;
        }

        $cliOptions = $this->request->getOptions();
        $options = [
            'dry_run' => array_key_exists('dryrun', $cliOptions),
            'force' => array_key_exists('force', $cliOptions),
            'json_file' => trim($cliOptions['json'] ?? ''),
        ];

        $libSync = new SlotSync_Lib();
        if(!$libSync->hasSyncColumn()){
            $this->out("WARNING: slot_game.sync_removed column is missing. Run app/Database/slot_game_sync.sql before a real run.");
        }

        $response = $libSync->syncTreem(trim($prdCode), $options);
        if($response['status'] != 1){
            $this->out("ERROR: ".$response['msg']);
            exit(1);
        }

        $bFail = false;
        foreach($response['results'] as $result){
            $this->printResult($result, $options['dry_run']);
            if($result['status'] != 1)
                $bFail = true;
        }

        $this->out($options['dry_run'] ? "Dry-run finished. No changes were written." : "Sync finished.");
        if($bFail)
            exit(1);
    }

    private function printResult($result, $bDryRun)
    {
        $this->out("");
        $this->out("[".$result['prd']."] ".$result['name']." (vendor: ".$result['vendor'].")");
        if($result['status'] != 1){
            $this->out("  SKIP: ".$result['msg']);
            return;
        }

        $this->out("  api=".$result['api_count']." db=".$result['db_count'].
            " added=".count($result['added'])." img=".count($result['img'])." name_ko=".count($result['name_ko']).
            " restored=".count($result['restored'])." hidden=".count($result['hidden']));
        if($result['hide_skipped'])
            $this->out("  WARNING: hiding skipped (more than 30% of visible games). Check the list and use --force if it is correct.");

        if(!$bDryRun)
            return;

        $this->printList("added", $result['added']);
        $this->printList("name_ko", $result['name_ko']);
        $this->printList("restored", $result['restored']);
        $this->printList("hidden", $result['hidden']);
    }

    private function printList($title, $items)
    {
        foreach($items as $item){
            $this->out("    ".$title.": ".$item);
        }
    }

    private function out($line)
    {
        echo $line.PHP_EOL;
    }
}
