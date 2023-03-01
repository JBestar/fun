<?php

class CasinoPrd_Model {

	private $mDbConn ;
	private $mTableName ;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "casino_prd";

	}

    public function getByCat($cat){
    	$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE cat = '".$cat."'";
    	
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



    

}

?>