<?php

class MoneyHistory_Model {

	private $mDbConn ;
	private $mTableName ;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "money_history";

	}

	public function insertRow($type, $objMember, $amount, $mode="", $target="", $fLog=null){

		// writeLog($fLog, "type=".$type);

		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (money_mb_fid, money_mb_uid, money_mb_emp_fid, money_amount, money_before, money_after, ";
        $strSql.= "money_change_type, money_bet_mode, money_bet_target, money_update_time ) VALUES "; 
		
		//money_mb_fid
		$strSql.= " ( '".$objMember->mb_fid."',";
		//money_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//money_mb_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//money_amount
		$strSql.= " '".$amount."', ";
		//money_before
		$strSql.= " '".allMoney($objMember)."', "; 
		//money_after
		$strSql.= " '".(allMoney($objMember)+$amount)."', "; 
		//money_change_type
		$strSql.= " '".$type."', ";
		//money_bet_mode
		$strSql.= " '".$mode."', ";
		//money_bet_target
		$strSql.= " '".$target."', ";
		 //money_update_time
		$strSql.= " '".date("Y-m-d H:i:s")."' ";
		$strSql.= " )";

		// writeLog($fLog, $strSql);

		try{
			if ($this->mDbConn->query($strSql) === TRUE) {
				return $this->mDbConn->insert_id;
			}
		} catch(mysqli_sql_exception $exception){
			return 0;
		}
		return 0;
    } 



    

}

?>