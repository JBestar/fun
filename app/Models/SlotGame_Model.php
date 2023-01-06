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

    public function getByRef($cat, $name){
        $where = [
            'cat' => $cat,
            'name' => trim($name),
            'open' => PERMIT_OK
        ];
        return $this->where($where)->first();
    }

}