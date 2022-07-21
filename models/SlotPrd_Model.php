<?php

class SlotPrd_Model {

	private $mDbConn ;
	private $mTableName ;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "slot_prd";

	}

    public function getByCat($cat){
    	$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE cat = '".$cat."'";
		$strSql.= " ORDER BY updated ASC, id ASC ";
    	
    	$arrResult = array();
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	array_push($arrResult, (object)$arrRow);
		  		}
			}
			$objResult->free();
		}
		return $arrResult;

    }

	public function updateLastTm($cat, $code, $tmLast){
    	$tmLast = trim($tmLast);
    	if(strlen($tmLast) < 1) return false;

    	$strSql = "UPDATE ".$this->mTableName." SET ";
		$strSql.= " updated = '".$tmLast."' ";
		$strSql.= " WHERE cat = '".$cat."' ";
		$strSql.= " AND code = '".$code."' ";

		return $this->mDbConn->query($strSql);

    }

	public function updateHistoryId($cat, $code, $id){
    	
		if(strlen($id) < 1) return false;

    	$strSql = "UPDATE ".$this->mTableName." SET ";
		$strSql.= " history_id = '".$id."' ";
		$strSql.= " WHERE cat = '".$cat."' ";
		$strSql.= " AND code = '".$code."' ";

		return $this->mDbConn->query($strSql);

    }


    

}

?>