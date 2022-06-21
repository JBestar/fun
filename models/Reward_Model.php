<?php

class Reward_Model {

	private $mDbConn ;
	private $mTableName ;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "bet_reward";

	}

	public function getLastFid($game=0){
		$strSql = "SELECT MAX(rw_fid) AS max_fid FROM ".$this->mTableName;
		if($game > 0)
			$strSql.= " WHERE rw_game = '".$game."' ";

    	$lastFid = 0;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	if(!is_null($arrRow['max_fid'])){
						$lastFid = $arrRow['max_fid'];
					}
		  		}
			}
			$objResult->free();
		}
		return $lastFid;

	}

	
    public function deleteByBetId($game, $betId, $lastFid){
    	$strSql = "DELETE FROM ".$this->mTableName;
    	$strSql.= " WHERE rw_fid > '".$lastFid."' ";
    	$strSql.= " AND rw_game = '".$game."' ";
    	$strSql.= " AND rw_bet_id = '".$betId."' ";

		return $this->mDbConn->query($strSql);

	}

    public function insert($gameId, $betId, $arrEmpRatio, $lastFid = 0, $bBlank = false){
    	
		if(is_null($arrEmpRatio) || count($arrEmpRatio) < 1)
			return true;

		$this->deleteByBetId($gameId, $betId, $lastFid);

		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (rw_game, rw_bet_id, rw_mb_fid, rw_mb_uid, rw_point, rw_state, rw_time ) VALUES ";
		// $dtNow = date("Y-m-d H:i:s");
		$idx = 0;
		foreach($arrEmpRatio as $ratio){
			if($ratio['point'] < 0)
				continue;
			if($idx > 0)
				$strSql.= ", ";
			//game
			$strSql.= " ( '".$gameId."',";
			//bet_id
			$strSql.= " '".$betId."', ";
			//mb_fid
			$strSql.= " '".$ratio['mb_fid']."', ";
			//mb_uid
			$strSql.= " '".$ratio['mb_uid']."', ";
			//point
			$strSql.= " '".$ratio['point']."' , ";
			//state
			$strSql.= ($bBlank ? " '1": "'0")."' , ";
			//time
			$strSql.= " '".$ratio['time']."' ) ";
			
			$idx ++;
			
		}

		return $this->mDbConn->query($strSql);
    } 
    

}

?>