<?php

class SlotGame_Model {

	private $mDbConn ;
	private $mTableName ;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "slot_game";

	}


	public function getByid($cat, $prd, $uuid){
		
		$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE cat = '".$cat."' ";
    	$strSql.= " AND prd_code = '".$prd."' ";
    	$strSql.= " AND uuid = '".$uuid."' ";
    	
    	$arrData = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrData = (object)$arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}

		return $arrData;
		
    }

	public function getByCode($cat, $prd, $code){
		
		$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE cat = '".$cat."' ";
    	$strSql.= " AND prd_code = '".$prd."' ";
    	$strSql.= " AND game_code = '".$code."' ";
    	
    	$arrData = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrData = (object)$arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}

		return $arrData;
		
    }

	//슬롯 게임추가
	public function insert_x($cat, $prd, $game){
    	
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (uuid, name, name_ko, type, provider, prd_code, cat, img, open ) VALUES ";
		
		//uuid		"uuid": "ppNew_509e44a2225e4ea8afa0958e5b296285",
		$strSql.= " ( '".$game['uuid']."',";
		//name		"name": "Queen of Atlantis",
		$strSql.= " '".str_replace("'", "\'", $game['name'])."', ";
		//nameKO	"nameKO": "퀸 오브 아틀란티스",
		$strSql.= " '".str_replace("'", "\'", $game['nameKO'])."', ";
		//type		"type": "SLOT",
		$strSql.= " '".$game['type']."', ";
		//provider	"provider": "PragmaticPlay",
		$strSql.= " '".$game['provider']."', ";
		//prd_code
		$strSql.= " '".$prd."', ";
		//cat
		$strSql.= " '".$cat."', ";
		//img
		$strSql.= " '".$game['img']."', ";
		//open
		$strSql.= " '1' ) ";

		try{
			return $this->mDbConn->query($strSql);
		} catch(mysqli_sql_exception $exception){

			return false;
		}
		return false;
    } 
  
	//내츄럴 슬롯 게임추가
    public function insert_f($cat, $prd, $game){
    	
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (uuid, name, name_ko, type, game_code, provider, prd_code, cat, img, open ) VALUES ";
		
		//uuid		"game_id": "1",
		$strSql.= " ( '".$game['game_id']."',";
		//name		"name": "Queen of Atlantis",
		$strSql.= " '".str_replace("'", "\'", $game['game_name_en'])."', ";
		//nameKO	"nameKO": "퀸 오브 아틀란티스",
		$strSql.= " '".str_replace("'", "\'", $game['game_name_ko'])."', ";
		//type		"type": "SLOT",
		$strSql.= " 'SLOT', ";
		//game_code		"game_code": "vs10runes",
		$strSql.= " '".$game['game_code']."', ";
		//provider	"provider": "PragmaticPlay",
		$strSql.= " '".$game['game_company']."', ";
		//prd_code
		$strSql.= " '".intval($game['prd_id'])."', ";
		//cat
		$strSql.= " '".$cat."', ";
		//img
		$strSql.= " '".$game['game_icon_url']."', ";
		//open
		$strSql.= " '".intval($game['open'])."' ) ";

		try{
			return $this->mDbConn->query($strSql);
		} catch(mysqli_sql_exception $exception){

			return false;
		}
		return false;
    } 
    

	  
}

?>