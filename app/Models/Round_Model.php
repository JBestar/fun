<?php 
namespace App\Models;

use CodeIgniter\Model;

class Round_Model extends Model {

    protected $table      = 'round_powerball';
    protected $primaryKey = 'round_fid';

    protected $returnType = 'object'; 

    public function setType($gameId){
        switch($gameId){
            case GAME_POWER_BALL:   $this->table = 'round_powerball';   break;
            case GAME_POWER_LADDER: $this->table = 'round_powerladder'; break;
            case GAME_BOGLE_BALL:   $this->table = 'round_bogleball';   break;
            case GAME_BOGLE_LADDER: $this->table = 'round_bogleladder'; break;
            case GAME_EOS5_BALL:    $this->table = 'round_eos5ball';    break;
            case GAME_EOS3_BALL:    $this->table = 'round_eos3ball';    break;
            case GAME_COIN5_BALL:   $this->table = 'round_coin5ball';   break;
            case GAME_COIN3_BALL:   $this->table = 'round_coin3ball';   break;
            case GAME_HAPPY_BALL:   $this->table = 'round_happyball';   break;
            default: break;
        }
    }

    
    public function gets($count){
        
        return $this->orderBy('round_fid', 'DESC')
                    ->findAll($count, 0);
    }

    
    public function searchCount($reqData){

        $where = "round_fid > '0' ";
        if(array_key_exists('date', $reqData) ){
            $where.= "AND round_date = '".$reqData['date']."' ";
        }
        $max = 1000;
        $data = $this->where($where)
                     ->findAll($max); 
        return count($data);
    }

    public function searchList($reqData){
        
        $where = "round_fid > 0 ";
        if(array_key_exists('date', $reqData) ){
            $where.= "AND round_date = '".$reqData['date']."' ";
        }
        
        $page = $reqData['page'];
        $count = $reqData['count'];
        
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        return $this->where($where)
                    ->orderBy('round_fid', 'DESC')
                    ->findAll($count, $count*($page-1)); 
    }
}
