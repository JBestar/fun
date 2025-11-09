<?php

class SlotBet_Model {

	private $mDbConn ;
	private $mTableName ;
	private $mMemberTable;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "bet_slot";
		$this->mMemberTable = "member";

	}


	public function getByIdx($strIdx, $iGame){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
        $strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE bet_idx = '".$strIdx."' ";
    	$strSql.= " AND bet_game_type = '".$iGame."' ";
		
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

		if(!is_null($arrData)){
			return $arrData['bet_idx'];
		}
		return 0;
    }
	//슬롯게임
	public function getByXslot($gameId, $bet, $fid = 0){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
        $strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE bet_fid >= '".$fid."' ";
    	$strSql.= " AND bet_game_id = '".$gameId."' ";
    	$strSql.= " AND bet_idx = '".$bet['LinkTransID']."' ";

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
	//내츄럴 슬롯
	public function getByFslot($gameId, $bet, $fid){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
        $strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE bet_fid >= '".$fid."' ";
    	$strSql.= " AND bet_game_id = '".$gameId."' ";
    	$strSql.= " AND bet_idx = '".$bet['history_id']."' ";

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
	//골드슬롯게임
	public function getByGslot($gameId, $bet, $fid = 0){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
		$strSql = "SELECT * FROM ".$this->mTableName;
		$strSql.= " WHERE bet_fid >= '".$fid."' ";
		$strSql.= " AND bet_game_id = '".$gameId."' ";
		$strSql.= " AND bet_idx = '".$bet['txn_id']."' ";

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
	//하이슬롯게임
	public function getByHslot($gameId, $bet, $fid = 0){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
		$strSql = "SELECT * FROM ".$this->mTableName;
		$strSql.= " WHERE bet_fid >= '".$fid."' ";
		$strSql.= " AND bet_game_id = '".$gameId."' ";
		$strSql.= " AND bet_idx = '".$bet['transactionID']."' ";

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
	
	//KGON슬롯게임
	public function getByKslot($gameId, $bet, $fid = 0){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
		$strSql = "SELECT * FROM ".$this->mTableName;
		$strSql.= " WHERE bet_fid >= '".$fid."' ";
		$strSql.= " AND bet_game_id = '".$gameId."' ";
		$strSql.= " AND bet_game_type = '".$bet['vendorId']."' ";
		$strSql.= " AND bet_idx = '".$bet['refId']."' ";

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

	//RAVE슬롯게임
	public function getByRslot($gameId, $prdId, $idx, $fid = 0){
		// SELECT bet_idx FROM bet_casino ORDER BY bet_fid DESC LIMIT 1
		$strSql = "SELECT * FROM ".$this->mTableName;
		$strSql.= " WHERE bet_fid >= '".$fid."' ";
		$strSql.= " AND bet_game_id = '".$gameId."' ";
		$strSql.= " AND bet_game_type = '".$prdId."' ";
		$strSql.= " AND bet_idx = '".$idx."' ";

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

    public function insertFslot($objMember, $bet, $bBlank = false, $nBlankPt = 0){
    	
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " point_amount, company_amount, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['history_id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '', ";
		//bet_time
		$strSql.= " '".strToLocal($bet['created_at'])."', ";	//UTC "2022-04-24T17:05:34.000Z"
		//bet_money
		$strSql.= " '".$bet['bet_money']."', ";
		//bet_win_money
		$strSql.= " '".$bet['win_money']."', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_code']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['gs_user_id']."', ";
		//bet_game_id
		$strSql.= " '".$bet['game_id']."', ";
		//bet_game_type
		$strSql.= " '".$bet['prd_id']."', ";
		//bet_table_code
		$strSql.= " '".$bet['game_code']."', ";
		//Blank point
		if($bBlank){
			$strSql.= " '1', ";
			$strSql.= " '".$nBlankPt."', ";
		} else {
			$strSql.= " '0', '0', ";
		}
		//ObjectID
		if(array_key_exists('history_id', $bet))
		{
			$strSql.= " '".$bet['history_id']."' ";
		} else {
			$strSql.= " 0 ";
		}
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 
	public function insertHslot($objMember, $bet, $bBlank = false, $nBlankPt = 0){
    	
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " point_amount, company_amount, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['transactionID']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['roundKey']."', ";
		//bet_time
		$strSql.= " '".$bet['regdate']."', ";	//UTC "2022-04-24T17:05:34.000Z"
		//bet_money
		$strSql.= " '".$bet['bet']."', ";
		//bet_win_money
		$strSql.= " '".$bet['win']."', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_code']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['playerID']."', ";
		//bet_game_id
		$strSql.= " '".$bet['game_id']."', ";
		//bet_game_type
		$strSql.= " '".$bet['prd_id']."', ";
		//bet_table_code
		$strSql.= " '".$bet['gameName']."', ";
		//Blank point
		if($bBlank){
			$strSql.= " '1', ";
			$strSql.= " '".$nBlankPt."', ";
		} else {
			$strSql.= " '0', '0', ";
		}
		//ObjectID
		$strSql.= " 0 ";
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 
	public function insertXslot($objMember, $bet, $bBlank = false, $nBlankPt = 0){
    	
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " point_amount, company_amount, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['LinkTransID']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['TransID']."', ";
		//bet_time
		$strSql.= " '".strToLocal($bet['Date'])."', "; //Local "2022-03-15 08:30:27"
		if($bet['Status'] === "BET"){
			//bet_money
			$strSql.= " '".$bet['Amount']."', ";
			//bet_win_money
			$strSql.= " '0', ";
		} else {
			//bet_money
			$strSql.= " '0', ";
			//bet_win_money
			$strSql.= " '".$bet['Amount']."', ";
		}
		
		//bet_agent_id
		$strSql.= " '".$bet['AgentID']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['PlayerID']."', ";
		//bet_game_id
		$strSql.= " '".$bet['game_id']."', ";
		//bet_game_type
		$strSql.= " '".$bet['ThirdParty']."', ";
		//bet_table_code
		$strSql.= " '".$bet['GameSymbol']."', ";
		//Blank point
		if($bBlank){
			$strSql.= " '1', ";
			$strSql.= " '".$nBlankPt."', ";
		} else {
			$strSql.= " '0', '0', ";
		}
		//ObjectID
		if(array_key_exists('ObjectID', $bet))
		{
			$strSql.= "'".$bet['ObjectID']."' ";
		} else {
			$strSql.= " 0 ";
		}
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 
    
	public function updateXslot($fid, $bet, $bBlank = false, $nBlankPt = 0){


		$strSql = "UPDATE ".$this->mTableName." SET ";	
		if($bet['Status'] === "BET"){
			$strSql.= " bet_money = '".$bet['Amount']."' ";
		} else {
			$strSql.= " bet_win_money = '".$bet['Amount']."' ";
		}
		if($bBlank){
			$strSql.= ", point_amount = '1' ";
			$strSql.= ", company_amount = '".$nBlankPt."' ";
		}

        $strSql.= " WHERE bet_fid = '".$fid."' ";
		
		return $this->mDbConn->query($strSql);
	}


	
	public function insertGslot($objMember, $bet, $bBlank = false, $nBlankPt = 0){
    	
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_choice, bet_result, point_amount, company_amount, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['txn_id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '', ";
		//bet_time
		$strSql.= " '".strToLocal($bet['created_at'])."', "; //Local "2022-03-15 08:30:27"
		if($bet['txn_type'] === "debit"){
			//bet_money
			$strSql.= " '".$bet['bet']."', ";
			//bet_win_money
			$strSql.= " '0', ";
		} else if($bet['txn_type'] === "credit") {
			//bet_money
			$strSql.= " '0', ";
			//bet_win_money
			$strSql.= " '".$bet['win']."', ";
		} else{
			//bet_money
			$strSql.= " '".$bet['bet']."', ";
			//bet_win_money
			$strSql.= " '".$bet['win']."', ";
		}
		
		//bet_agent_id
		$strSql.= " '".$bet['agent_code']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user_code']."', ";
		//bet_game_id
		$strSql.= " '".$bet['game_id']."', ";
		//bet_game_type
		$strSql.= " '".$bet['provider']."', ";
		//bet_table_code
		$strSql.= " '".$bet['game_code']."', ";
		//start_balance
		$strSql.= " '".$bet['user_before_balance']."', ";
		//end_balance
		$strSql.= " '".$bet['user_after_balance']."', ";
		//Blank point
		if($bBlank){
			$strSql.= " '1', ";
			$strSql.= " '".$nBlankPt."', ";
		} else {
			$strSql.= " '0', '0', ";
		}
		$strSql.= " 0 ";
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 
    
	public function updateGslot($fid, $bet, $bBlank = false, $nBlankPt = 0){


		$strSql = "UPDATE ".$this->mTableName." SET ";	
		if($bet['txn_type'] === "debit"){
			//bet_money
			$strSql.= " bet_money = '".$bet['bet']."' ";
		} else if($bet['txn_type'] === "credit") {
			//bet_win_money
			$strSql.= " bet_win_money = '".$bet['win']."' ";
		} else{
			//bet_money
			$strSql.= " bet_money = '".$bet['bet']."', ";
			//bet_win_money
			$strSql.= " bet_win_money = '".$bet['win']."' ";
		}

		if($bBlank){
			$strSql.= ", point_amount = '1' ";
			$strSql.= ", company_amount = '".$nBlankPt."' ";
		}

        $strSql.= " WHERE bet_fid = '".$fid."' ";
		
		return $this->mDbConn->query($strSql);
	}
	
	public function insertKslot($objMember, $bet, $bBlank = false, $nBlankPt = 0){
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_choice, bet_result, point_amount, company_amount, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['_id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '', ";
		//bet_time
		$strSql.= " '".$bet['createdAt']."', "; //Local "2022-03-15 08:30:27"
		//bet_money
		$strSql.= " '".$bet['cash']."', ";
		//bet_win_money
		$strSql.= " '0', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user_id']."', ";
		//bet_game_id
		$strSql.= " '".$bet['game_id']."', ";
		//bet_game_type
		$strSql.= " '".$bet['vendorId']."', ";
		//bet_table_code
		$strSql.= " '".$bet['gameName']."', ";
		//beforeCash
		$strSql.= " '".$bet['beforeCash']."', ";
		//afterCash
		$strSql.= " '', ";
		//Blank point
		if($bBlank){
			$strSql.= " '1', ";
			$strSql.= " '".$nBlankPt."', ";
		} else {
			$strSql.= " '0', '0', ";
		}
		$strSql.= " 0 ";
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 
    
	public function updateKslot($fid, $bet, $bBlank = false, $nBlankPt = 0){


		$strSql = "UPDATE ".$this->mTableName." SET ";	
		if($bet['type'] === "turn_bet"){
			//bet_money
			$strSql.= " bet_money = '".$bet['cash']."' ";
		} else if($bet['type'] !== "turn_lose"){
			//bet_win_money
			$strSql.= " bet_win_money = '".$bet['cash']."' ";
		} 
		//bet_result
		$strSql.= ", bet_result = '".$bet['afterCash']."' ";
		if($bBlank){
			$strSql.= ", point_amount = '1' ";
			$strSql.= ", company_amount = '".$nBlankPt."' ";
		}

        $strSql.= " WHERE bet_fid = '".$fid."' ";
		
		return $this->mDbConn->query($strSql);
	}

	public function insertRslot($objMember, $bet, $bBlank = false, $nBlankPt = 0){
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_choice, bet_result, point_amount, company_amount, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '', ";
		//bet_time
		$strSql.= " '".$bet['betAt']."', "; //Local "2022-03-15 08:30:27"
		//bet_money
		$strSql.= " '".$bet['amount']."', ";
		//bet_win_money
		$strSql.= " '0', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user']['id']."', ";
		//bet_game_id
		$strSql.= " '".$bet['game_id']."', ";
		//bet_game_type
		$strSql.= " '".$bet['prd_id']."', ";
		//bet_table_code
		$strSql.= " '".$bet['game']['code']."', ";
		//beforeCash
		$strSql.= " '".$bet['balance']."', ";
		//afterCash
		$strSql.= " '', ";
		//Blank point
		if($bBlank){
			$strSql.= " '1', ";
			$strSql.= " '".$nBlankPt."', ";
		} else {
			$strSql.= " '0', '0', ";
		}
		$strSql.= " 0 ";
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 
    
	public function updateRslot($fid, $bet, $bBlank = false, $nBlankPt = 0){


		$strSql = "UPDATE ".$this->mTableName." SET ";	
		if(strtolower($bet['type']) == "bet"){
			//bet_money
			$strSql.= " bet_money = '".$bet['amount']."', ";
		} else if($bet['amount'] > 0){
			//bet_win_money
			$strSql.= " bet_win_money = '".$bet['amount']."', ";
		} 
		//bet_result
		$strSql.= " bet_result = '".$bet['id']."' ";
		if($bBlank){
			$strSql.= ", point_amount = '1' ";
			$strSql.= ", company_amount = '".$nBlankPt."' ";
		}

        $strSql.= " WHERE bet_fid = '".$fid."' ";
		
		return $this->mDbConn->query($strSql);
	}

	public function insertTslot($objMember, $bet, $bBlank = false, $nBlankPt = 0){
		// $strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql = "INSERT IGNORE INTO ".$this->mTableName." (bet_idx, bet_emp_fid, bet_mb_uid, bet_round_no, bet_time, ";
		$strSql.= " bet_money, bet_win_money, bet_agent_id, bet_player_id, bet_game_id, bet_game_type, bet_table_code, ";
		$strSql.= " bet_choice, bet_result, point_amount, company_amount, obj_id ) VALUES "; 
		
		//bet_idx
		$strSql.= " ( '".$bet['id']."',";
		//bet_emp_fid
		$strSql.= " '".$objMember->mb_emp_fid."', ";
		//bet_mb_fid
		// $strSql.= " '".$objMember->mb_fid."', ";
		//bet_mb_uid
		$strSql.= " '".$objMember->mb_uid."', ";
		//bet_round_no
		$strSql.= " '".$bet['details']['game']['round']."', ";
		//bet_time
		$strSql.= " '".$bet['created_at']."', "; //Local "2022-03-15 08:30:27"
		//bet_money
		$strSql.= " '".$bet['amount']."', ";
		//bet_win_money
		$strSql.= " '0', ";
		//bet_agent_id
		$strSql.= " '".$bet['agent_id']."', "; 
		//bet_player_id
		$strSql.= " '".$bet['user']['username']."', ";
		//bet_game_id
		$strSql.= " '".$bet['game_id']."', ";
		//bet_game_type
		$strSql.= " '".$bet['prd_id']."', ";
		//bet_table_code
		$strSql.= " '".$bet['details']['game']['title']."', ";
		//beforeCash
		$strSql.= " '".$bet['before']."', ";
		//afterCash
		$strSql.= " '', ";
		//Blank point
		if($bBlank){
			$strSql.= " '1', ";
			$strSql.= " '".$nBlankPt."', ";
		} else {
			$strSql.= " '0', '0', ";
		}
		$strSql.= " 0 ";
		$strSql.= " )";

		if ($this->mDbConn->query($strSql) === TRUE) {
			return $this->mDbConn->insert_id;
		}
		return 0;
    } 
    
}

?>