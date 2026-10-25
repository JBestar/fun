<?php 
namespace App\Libraries;

use App\Models\SlotPrd_Model;
use App\Models\SlotGame_Model;

class SlotSync_Lib  {

    const HIDE_RATIO_LIMIT = 0.3;       // skip hiding when more than 30% of visible games would be hidden
    const API_DELAY_US = 300000;
    const LOG_HEAD = "<SLOT_SYNC> ";

    private $modelSlotprd;
    private $modelSlotgame;
    private $libApiTreem;
    private $bSyncColumn = false;

    function __construct()
    {
        $this->modelSlotprd = new SlotPrd_Model();
        $this->modelSlotgame = new SlotGame_Model();
        $this->libApiTreem = new ApiTreem_Lib();

        $db = \Config\Database::connect();
        $this->bSyncColumn = $db->fieldExists('sync_removed', 'slot_game');
    }

    public function hasSyncColumn(){
        return $this->bSyncColumn;
    }

    // $options : dry_run(bool), force(bool), json_file(string, single provider only)
    public function syncTreem($prdCode = '', $options = [])
    {
        $cat = GAME_SLOT_TREEM;
        $bDryRun = !empty($options['dry_run']);
        $jsonFile = $options['json_file'] ?? '';

        if(!$this->bSyncColumn && !$bDryRun){
            return ['status' => 0, 'msg' => "slot_game.sync_removed column is missing. Run app/Database/slot_game_sync.sql first."];
        }
        if(strlen($jsonFile) > 0 && strlen($prdCode) < 1){
            return ['status' => 0, 'msg' => "json file can be used with a single provider code only."];
        }

        $prds = $this->modelSlotprd->getsForSync($cat, $prdCode);
        if(count($prds) < 1){
            return ['status' => 0, 'msg' => "No provider found. cat=".$cat." prd=".$prdCode];
        }

        writeLog(self::LOG_HEAD."Start cat=".$cat." prd=".$prdCode." providers=".count($prds).($bDryRun ? " (dry-run)" : ""));

        $results = [];
        foreach($prds as $idx => $prd){
            if($idx > 0 && strlen($jsonFile) < 1)
                usleep(self::API_DELAY_US);
            $results[] = $this->syncPrd($cat, $prd, $options);
        }

        writeLog(self::LOG_HEAD."End cat=".$cat);
        return ['status' => 1, 'results' => $results];
    }

    private function syncPrd($cat, $prd, $options)
    {
        $bDryRun = !empty($options['dry_run']);
        $bForce = !empty($options['force']);
        $jsonFile = $options['json_file'] ?? '';

        $result = [
            'prd' => $prd->code, 'name' => $prd->name, 'vendor' => '', 'status' => 0, 'msg' => '',
            'api_count' => 0, 'db_count' => 0,
            'added' => [], 'img' => [], 'name_ko' => [], 'restored' => [], 'hidden' => [], 'hide_skipped' => false,
        ];

        $rows = $this->modelSlotgame->getsByPrd($cat, $prd->code);
        $result['db_count'] = count($rows);

        $apiGames = null;
        foreach($this->getVendors($prd, $rows) as $vendor){
            $result['vendor'] = $vendor;
            if(strlen($jsonFile) > 0)
                $arrResult = $this->loadJson($jsonFile);
            else
                $arrResult = $this->libApiTreem->getGameList($vendor);
            if($arrResult['status'] == 1 && count($arrResult['games']) > 0){
                $apiGames = $arrResult['games'];
                break;
            }
        }
        if(is_null($apiGames)){
            $result['msg'] = "API failed or empty list";
            writeLog(self::LOG_HEAD."[".$prd->code."] ".$prd->name." SKIP: ".$result['msg']);
            return $result;
        }

        $apiById = [];
        foreach($apiGames as $game){
            if(!is_array($game) || empty($game['id']) || empty($game['type']) || $game['type'] == 'lobby')
                continue;
            if(strlen($game['id']) > 50)
                continue;
            $apiById[$game['id']] = $game;
        }
        $result['api_count'] = count($apiById);
        if($result['api_count'] < 1){
            $result['msg'] = "No playable game in API list";
            writeLog(self::LOG_HEAD."[".$prd->code."] ".$prd->name." SKIP: ".$result['msg']);
            return $result;
        }

        $inserts = [];
        $updates = [];
        $hideFids = [];
        $dbCodes = [];
        $visibleCount = 0;

        foreach($rows as $row){
            $dbCodes[$row->game_code] = true;
            $syncRemoved = $this->bSyncColumn ? intval($row->sync_removed) : 0;
            if($row->hidden == STATE_DISABLE)
                $visibleCount++;

            if(!array_key_exists($row->game_code, $apiById)){
                if($row->hidden == STATE_DISABLE){
                    $hideFids[] = $row->fid;
                    $result['hidden'][] = $row->game_code." | ".$row->name_ko;
                }
                continue;
            }

            $game = $apiById[$row->game_code];
            $data = [];

            $thumbnail = trim($game['thumbnail'] ?? '');
            if(strlen($thumbnail) > 0 && strlen($thumbnail) <= 400 && $thumbnail != $row->img){
                $data['img'] = $thumbnail;
                $result['img'][] = $row->game_code;
            }

            $nameKo = $this->nameKo($game);
            if($row->name_ko == $row->name && strlen($nameKo) > 0 && $nameKo != $row->name_ko){
                $data['name_ko'] = $nameKo;
                $result['name_ko'][] = $row->game_code." | ".$row->name_ko." => ".$nameKo;
            }

            if($row->hidden == STATE_ACTIVE && $syncRemoved == STATE_ACTIVE){
                $data['hidden'] = STATE_DISABLE;
                $data['sync_removed'] = STATE_DISABLE;
                $result['restored'][] = $row->game_code." | ".$row->name_ko;
            }

            if(count($data) > 0)
                $updates[$row->fid] = $data;
        }

        foreach($apiById as $id => $game){
            if(array_key_exists($id, $dbCodes))
                continue;

            $title = mb_substr(trim($game['title'] ?? $id), 0, 50);
            $nameKo = $this->nameKo($game);
            $insert = [
                'uuid' => $id,
                'name' => $title,
                'name_ko' => strlen($nameKo) > 0 ? $nameKo : $title,
                'type' => mb_substr($game['type'], 0, 30),
                'game_code' => $id,
                'provider' => $result['vendor'],
                'prd_code' => $prd->code,
                'cat' => $cat,
                'img' => mb_substr(trim($game['thumbnail'] ?? ''), 0, 400),
                'open' => PERMIT_OK,
                'act' => STATE_DISABLE,
                'hidden' => STATE_DISABLE,
                'maintain' => STATE_DISABLE,
                'ref_prd' => 0,
                'ref_uuid' => '',
            ];
            if($this->bSyncColumn)
                $insert['sync_removed'] = STATE_DISABLE;
            $inserts[] = $insert;
            $result['added'][] = $id." | ".$insert['name_ko'];
        }

        if(!$bForce && $visibleCount > 0 && count($hideFids) > $visibleCount * self::HIDE_RATIO_LIMIT){
            $result['hide_skipped'] = true;
            writeLog(self::LOG_HEAD."[".$prd->code."] ".$prd->name." WARN: hide skipped. hide=".count($hideFids)." visible=".$visibleCount);
            $hideFids = [];
        }

        if(!$bDryRun && (count($inserts) > 0 || count($updates) > 0 || count($hideFids) > 0)){
            if(!$this->modelSlotgame->applySync($inserts, $updates, $hideFids)){
                $result['msg'] = "DB transaction failed";
                writeLog(self::LOG_HEAD."[".$prd->code."] ".$prd->name." ERROR: ".$result['msg']);
                return $result;
            }
        }

        $result['status'] = 1;
        writeLog(self::LOG_HEAD."[".$prd->code."] ".$prd->name." vendor=".$result['vendor'].
            " api=".$result['api_count']." db=".$result['db_count'].
            " added=".count($result['added'])." img=".count($result['img'])." name_ko=".count($result['name_ko']).
            " restored=".count($result['restored'])." hidden=".($result['hide_skipped'] ? "0(skipped ".count($result['hidden']).")" : count($result['hidden'])).
            ($bDryRun ? " (dry-run)" : ""));

        return $result;
    }

    // provider stored in slot_game is what game launch sends as vendor, so it takes priority over slot_prd.name
    private function getVendors($prd, $rows)
    {
        $counts = [];
        foreach($rows as $row){
            $provider = trim($row->provider);
            if(strlen($provider) > 0)
                $counts[$provider] = ($counts[$provider] ?? 0) + 1;
        }
        arsort($counts);

        $vendors = array_keys($counts);
        if(count($vendors) > 0)
            $vendors = [$vendors[0]];
        if(!in_array(trim($prd->name), $vendors))
            $vendors[] = trim($prd->name);
        return $vendors;
    }

    private function nameKo($game)
    {
        if(isset($game['langs']['ko']) && is_string($game['langs']['ko']))
            return mb_substr(trim($game['langs']['ko']), 0, 50);
        return '';
    }

    private function loadJson($jsonFile)
    {
        if(!is_file($jsonFile))
            return ['status' => 0];
        $games = json_decode(file_get_contents($jsonFile), true);
        if(!is_array($games))
            return ['status' => 0];
        return ['status' => 1, 'games' => $games];
    }
}
