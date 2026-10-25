<?php 
namespace App\Models;

use CodeIgniter\Model;

class SlotGame_Model extends Model {

    protected $table      = 'slot_game';
    protected $primaryKey = 'fid';

    protected $returnType = 'object'; 

    public function getById($cat, $prd, $uuid){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'uuid' => $uuid,
            'hidden' => STATE_DISABLE,
            'open' => PERMIT_OK
        ];
        return $this->where($where)->first();
    }

    public function gets($cat, $prd){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'open' => PERMIT_OK, 
            'hidden' => STATE_DISABLE
        ];
        
        return $this->where($where)
                    // ->orderBy('name', 'ASC')
                    ->findAll(); 
    }

    public function getByName($cat, $prd, $name){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'name' => trim($name),
            'open' => PERMIT_OK
        ];
        return $this->where($where)->first();
    }

    public function getByNameKo($cat, $prd, $name){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
            'name_ko' => trim($name),
            'open' => PERMIT_OK
        ];
        return $this->where($where)->first();
    }

    public function getByRef($cat, $prd, $uuid){
        $where = [
            'cat' => $cat,
            'ref_prd' => $prd,
            'ref_uuid' => $uuid,
            'open' => PERMIT_OK,
            'hidden' => STATE_DISABLE
        ];

        $gameFslot = null;
        $games = $this->where($where)->findAll();
        foreach($games as $game){
            if($game->act == STATE_ACTIVE){
                $gameFslot = $game;
                break;
            }
        }
        return $gameFslot;
    }

    public function getsByPrd($cat, $prd){
        $where = [
            'cat' => $cat,
            'prd_code' => $prd,
        ];
        return $this->where($where)
                    ->orderBy('fid', 'ASC')
                    ->findAll();
    }

    public function applySync($inserts, $updates, $hideFids){
        $builder = $this->builder();

        $this->db->transStart();
        if(count($inserts) > 0)
            $builder->insertBatch($inserts);

        foreach($updates as $fid => $data){
            $builder->where('fid', $fid)->update($data);
        }

        if(count($hideFids) > 0){
            $builder->whereIn('fid', $hideFids)
                    ->update(['hidden' => STATE_ACTIVE, 'sync_removed' => STATE_ACTIVE]);
        }
        $this->db->transComplete();

        return $this->db->transStatus();
    }

}