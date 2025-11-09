<?php

class Transfer_Model {

	private $mDbConn ;
	private $mTableName ;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "money_transfer";

	}

	public function insertRow($type, $objMember, $gPoint, $balance){

		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (trans_mb_fid, trans_mb_uid, trans_emp_fid, trans_amount, 
        money_before, money_after, egg_before, egg_after, trans_type, trnas_time ) VALUES "; 
		
		//trans_mb_fid
		$strSql.= " ( '".$objMember->mb_fid."',";
		//trans_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//trans_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//trans_amount
		$strSql.= " '".$balance."', ";
		//money_before
		$strSql.= " '".$objMember->mb_money."', "; 
		//money_after
		$strSql.= " '".($objMember->mb_money-$balance)."', "; 
		//money_before
		$strSql.= " '".$gPoint."', "; 
		//money_after
		$strSql.= " '".($gPoint+$balance)."', ";
		//trans_type
		$strSql.= " '".$type."', ";
		 //trnas_time
		$strSql.= " '".$date("Y-m-d H:i:s")."', ";
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 



    

}

?>