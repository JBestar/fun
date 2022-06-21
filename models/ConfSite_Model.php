<?php

class ConfSite_Model {

	private $mDbConn ;
	private $mTableName ;
	
	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "conf_site";
	

	}

	public function getById($conf_id){
		
        $strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE conf_id = '".$conf_id."' ";
    	
    	$objConfig = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$objConfig = (object)$arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}
		return $objConfig;
    }


    public function updateLastTm($conf_id, $tmLast){
    	$tmLast = trim($tmLast);
    	if(strlen($tmLast) < 1) return false;

    	$strSql = "UPDATE ".$this->mTableName." SET ";
		$strSql.= " conf_update = '".$tmLast."' ";
		$strSql.= " WHERE conf_id = '".$conf_id."' ";

		return $this->mDbConn->query($strSql);

    }

	
    public function updateLastIdx($conf_id, $lastIdx){

    	$strSql = "UPDATE ".$this->mTableName." SET ";
		$strSql.= " conf_update = NOW(),";
		$strSql.= " conf_idx = '".$lastIdx."' ";

		$strSql.= " WHERE conf_id = '".$conf_id."' ";

		return $this->mDbConn->query($strSql);

    }

	public function updateActive($conf_id, $value){
    	
		
    	$strSql = "UPDATE ".$this->mTableName." SET ";
		$strSql.= " conf_active = '".$value."' ";
		$strSql.= " WHERE conf_id = '".$conf_id."' ";

		return $this->mDbConn->query($strSql);

    }


}

?>