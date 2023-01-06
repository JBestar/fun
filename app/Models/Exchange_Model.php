<?php 
namespace App\Models;

use CodeIgniter\Model;

class Exchange_Model extends Model {

    protected $table      = 'member_exchange';
    protected $primaryKey = 'exchange_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['exchange_emp_fid', 'exchange_mb_uid', 'exchange_mb_phone', 'exchange_money', 
        'exchange_time_require', 'exchange_action_state', 'exchange_action_uid', 'exchange_time_process',
        'exchange_bank_name', 'exchange_bank_account', 'exchange_bank_serial',
        'exchange_money_before', 'exchange_money_after',  'exchange_state_delete', 'exchange_client_delete']; 

    public function register($data)
    {
        try {
            return $this->insert($data);
        } catch (\Exception $e) {  
            return false;
        }
        return false;

    }

    public function wait($user_id){
        $where = "exchange_mb_uid = '".$user_id."' ";
        $where.= " AND exchange_client_delete = '".STATE_DISABLE."' ";
        $where.= " AND (exchange_action_state = '".STATE_ACTIVE."' OR exchange_action_state = '".STATE_WAIT."') ";

        $exchange = $this->where($where)
                    ->first();

        return !is_null($exchange);
    }

    public function searchCount($reqData){

        $where = " WHERE exchange_time_require >= '".$reqData['start_at']."' ";
        $where.= " AND exchange_time_require <= '".$reqData['end_at']."' ";
        $where.= " AND exchange_mb_uid = '".$reqData['req_uid']."' ";
        
        $strSql = "SELECT count('exchange_fid') as count FROM ".$this->table;
        $strSql .= $where;

        $query = $this -> db -> query($strSql);
        $result = $query -> getRow();
        
        return $result->count; 
    }

    public function searchList($reqData){
        
        $getFields = ['exchange_fid', 'exchange_mb_uid', 'exchange_money', 'exchange_time_require', 
            'exchange_action_state', 'exchange_action_uid', 'exchange_time_process',
            'exchange_bank_name', 'exchange_bank_account', 'exchange_bank_serial',
            'exchange_money_before', 'exchange_money_after' ]; 
        
        $where = " WHERE exchange_time_require >= '".$reqData['start_at']."' ";
        $where.= " AND exchange_time_require <= '".$reqData['end_at']."' ";
        $where.= " AND exchange_mb_uid = '".$reqData['req_uid']."' ";
        
        $strTbColum = " ".implode(", ", $getFields);
        
        $strSql = " SELECT ".$strTbColum." FROM ".$this->table;
        $strSql .= $where;
        
        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;
        
        $nStartRow = ($page-1) * $count ;

        $strSql.=" ORDER BY exchange_fid DESC LIMIT ".$nStartRow.", ".$count;

        $query = $this -> db -> query($strSql);
        $result = $query -> getResult();
        return $result;
    }
}