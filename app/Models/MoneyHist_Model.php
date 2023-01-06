<?php 
namespace App\Models;

use CodeIgniter\Model;

class MoneyHist_Model extends Model {

    protected $table      = 'money_history';
    protected $primaryKey = 'money_fid';

    protected $returnType = 'object'; 
    protected $allowedFields = ['money_mb_fid', 'money_mb_uid', 'money_mb_emp_fid', 'money_amount', 
        'money_before', 'money_after', 'money_change_type', 'money_bet_round', 'money_bet_mode',
        'money_bet_target', 'money_update_time'];

    
    function registerPointToMoney($objMember, $nPoint)
    {
        if($nPoint == 0)
            return true;

        try {             
            $data = [  
                'money_mb_fid' => $objMember->mb_fid,
                'money_mb_uid' => $objMember->mb_uid,
                'money_mb_emp_fid' => $objMember->mb_emp_fid,
                'money_amount' => $nPoint,
                'money_before' => allMoney($objMember),
                'money_after' => allMoney($objMember) + $nPoint ,
                'money_change_type' => POINTCHANGE_EXCHANGE,
                'money_update_time' => date("Y-m-d H:i:s")
            ];

            return $this->insert($data);
        } catch (\Exception $e) {  
            return false;
        }
        return false;
        
    }

    
    function registerBet($objMember, $arrBetData, $type)
    {
        $amount = 0 - $arrBetData['amount'];
        
        try {             
            $data = [  
                'money_mb_fid' => $objMember->mb_fid,
                'money_mb_uid' => $objMember->mb_uid,
                'money_mb_emp_fid' => $objMember->mb_emp_fid,
                'money_amount' => $amount,
                'money_before' => allMoney($objMember),
                'money_after' => allMoney($objMember) + $amount ,
                'money_change_type' => $type,
                'money_bet_round'=> $arrBetData['roundno'],
                'money_bet_mode'=> $arrBetData['mode'],
                'money_bet_target'=> $arrBetData['target'],
                'money_update_time' => date("Y-m-d H:i:s")
            ];

            return $this->insert($data);
        } catch (\Exception $e) {  
            // $query = $this->db->getLastQuery();
            return false;
        }
        return false;
    }

    function register($objMember, $amount, $type)
    {
        try {             
            $data = [  
                'money_mb_fid' => $objMember->mb_fid,
                'money_mb_uid' => $objMember->mb_uid,
                'money_mb_emp_fid' => $objMember->mb_emp_fid,
                'money_amount' => $amount,
                'money_before' => allMoney($objMember),
                'money_after' => allMoney($objMember) + $amount ,
                'money_change_type' => $type,
                'money_update_time' => date("Y-m-d H:i:s")
            ];

            return $this->insert($data);
        } catch (\Exception $e) {  
            // $query = $this->db->getLastQuery();
            return false;
        }
        return false;
    }

}