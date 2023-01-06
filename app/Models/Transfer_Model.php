<?php 
namespace App\Models;

use CodeIgniter\Model;

class Transfer_Model extends Model {

    protected $table      = 'transfer_history';
    protected $primaryKey = 'money_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['money_mb_fid', 'money_mb_uid', 'money_mb_emp_fid', 'money_amount', 
        'money_site_before', 'money_site_after', 'money_live_before', 'money_live_after', 'money_change_type', 
        'money_update_time'];

    public function register($type, $objUser, $gPoint, $balance){
        if(is_null($objUser)) return false;    
        if($balance == 0) return true;

        $data = [
            'money_mb_fid' => $objUser->mb_fid,
            'money_mb_uid' => $objUser->mb_uid,
            'money_mb_emp_fid' => $objUser->mb_emp_fid,
            'money_amount' => $balance,
            'money_site_before' => $objUser->mb_money,
            'money_site_after' => $objUser->mb_money - $balance,
            'money_live_before' => $gPoint,
            'money_live_after' => $gPoint + $balance,
            'money_change_type' => $type,
            'money_update_time' => date("Y-m-d H:i:s"),
        ];

        return $this->insert($data);
    }

    public function searchCount($reqData){

        $where = "money_mb_uid = '".$reqData['req_uid']."' ";
        $data = $this->where($where)
                     ->findAll(); 
        return count($data);
    }
 
    
    public function searchList($reqData){
        $where = "money_mb_uid = '".$reqData['req_uid']."' ";

        $page = $reqData['page'];
        $count = $reqData['count'];
        if($page < 1)
            return NULL;
        if($count < 1)
            return NULL;


        return $this->where($where)
            ->orderBy('money_fid', 'DESC')
            ->findAll($count, $count*($page-1)); 
    }

}