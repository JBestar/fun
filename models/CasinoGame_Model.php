<?php

class CasinoGame_Model {

	private $mDbConn ;
	private $mTableName ;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "casino_game";

	}


	public function getByTid($tid){
		
		$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE tid = '".$tid."' ";
    	
    	$arrData = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrData = $arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}

		return $arrData;
		
    }

    public function insert($table){
    	
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (tid, name, ribbon, min_bet, max_bet ) VALUES ";
		
		//tid
		$strSql.= " ( '".$table['tableid']."',";
		//name
		$strSql.= " '".$table['name']."', ";
		//ribbon
		$strSql.= " '".$table['ribbon']."', ";
		//min_bet
		$strSql.= " '".$table['minbetlimit']."', ";
		//max_bet
		$strSql.= " '".$table['maxbetlimit']."' ) ";
		

		return $this->mDbConn->query($strSql);
    } 
    

}

?>