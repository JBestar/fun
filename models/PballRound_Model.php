<?php

class PballRound_Model {

	private $mDbConn ;
	private $mTableName ;
	

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "round_powerball";
		
	}

	public function getByFid($nRoundFid){

    	$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE round_fid = '".$nRoundFid."' ";
    	
    	$arrResult = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrResult = $arrRow;
			  	}
			}
			$objResult->free();
		}
		return $arrResult;
    }
	

    public function getByDate($nRoundNo, $strDate){

    	$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE round_num = '".$nRoundNo."' ";
    	$strSql.= " AND round_date = '".$strDate."' ";

    	$arrResult = null;
    	if($objResult = $this->mDbConn->query($strSql)){
    	
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrResult = $arrRow;
			  	}
			}
			$objResult->free();
		}
		return $arrResult;
    }
	

	public function getLast(){
		$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " ORDER BY round_fid DESC LIMIT 1"; 

    	$objResult = $this->mDbConn->query($strSql);

    	$arrResult = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$arrResult = $arrRow;
		  		}
			}
			$objResult->free();
		}
		return $arrResult;
	}

	public function registerEmptyRound($arrRoundInfo){
		//자료기지체크         
		$arrRound = $this->getByDate($arrRoundInfo['round_no'], $arrRoundInfo['round_date']);
        
        if(!is_null($arrRound))
        {
        	$arrRoundInfo['round_fid'] = $arrRound['round_fid'];
        	$arrRoundInfo['round_state'] = $arrRound['round_state'];
        	return $arrRoundInfo;
        }

        $arrRoundInfo['round_fid'] = "10001";
        $arrRoundInfo['round_state'] = 0;
        $arrRound = $this->getLast();        
        if(!is_null($arrRound))
        {
        	$arrRoundInfo['round_fid'] = $arrRound['round_fid'] + 1;
        }

        $strSql = "INSERT INTO ".$this->mTableName." (round_fid, round_date, round_num, round_time, round_state) ";
		$strSql .= " VALUES ('".$arrRoundInfo['round_fid']."', '".$arrRoundInfo['round_date']."', '";
		$strSql .= $arrRoundInfo['round_no']."', NOW(), '".$arrRoundInfo['round_state']."' )";
		
		if ($this->mDbConn->query($strSql) === TRUE) {
			return $arrRoundInfo;
		}

		return null; 

        
	}

	
	public function registerRound($arrRoundInfo, $arrRoundResult)
	{


		if(is_null($arrRoundInfo) || is_null($arrRoundResult))
			return 0;
		//이미 등록되있으면 패스        
		if($arrRoundInfo['round_state'] == 1)
        {
        	return $arrRoundInfo['round_fid'];
        }

		if(!array_key_exists("date", $arrRoundResult) || !array_key_exists("date_round", $arrRoundResult))
			return 0;
		
		//날자읽기
		$strDate = $arrRoundResult['date'];
		if(empty($strDate) || $strDate !== $arrRoundInfo['round_date'])
			return 0;

		//일별회차번호 읽기
		$strRoundNo = $arrRoundResult['date_round'];
		if(empty($strRoundNo) || $strRoundNo != $arrRoundInfo['round_no'])
			return 0;

        //유일회차번호
    	$nRoundFid = $arrRoundResult['times'];
    	if(empty($nRoundFid) || $nRoundFid < 1)
			return 0;
		$arrRoundInfo['round_fid'] =  $nRoundFid;

		//회차결과 수자들 따내기
		$arrRoundNumbers = $arrRoundResult['ball'];

		if(empty($arrRoundNumbers) || !is_array($arrRoundNumbers))
			return 0;
		
		$nCount = count($arrRoundNumbers);

		if($nCount != 6)
			return 0;

		//일반볼 문자열, 일반볼 합계산
		$nNorBallSum = 0;
		$strNorball = "";
		for ($i = 0 ; $i < $nCount-1 ; $i ++) 
		{
			if(is_numeric($arrRoundNumbers[$i]))
			{
				$nNorBallSum += $arrRoundNumbers[$i];
				$strNorball .= $arrRoundNumbers[$i].",";
			}
			else return 0;
		}

		$strNorball = substr($strNorball, 0, strlen($strNorball)-1);

		if(!is_string($arrRoundNumbers[5]))
			return 0;

		$nPowerball = (int)$arrRoundNumbers[5];

		$strSql = "UPDATE ".$this->mTableName." SET ";
		$strSql.= " round_fid = '" .$arrRoundInfo['round_fid']."', ";
		$strSql.= " round_state = '1', ";		
		//Round Result
		$strResult1 = ($nPowerball % 2) ? 'P' : 'B';	//Powerball ODD or Even
		$strSql.= " round_result_1 = '" .$strResult1."', ";

		$strResult2 = ($nPowerball < 5) ? 'P' : 'B';	//Powerball UNDER or OVER				
		$strSql.= " round_result_2 = '" .$strResult2."', ";
		
		$strResult3 = ($nNorBallSum % 2) ? 'P' : 'B';	//Normalball ODD or Even
		$strSql.= " round_result_3 = '" .$strResult3."', ";

		$strResult4 = ($nNorBallSum <= 72) ? 'P' : 'B';	//Normalball UNDER or OVER
		$strSql.= " round_result_4 = '" .$strResult4."', ";

		//Large, Medium, Small
		$strResult5 = 'X';
		if($nNorBallSum >=15 && $nNorBallSum <= 64)
			$strResult5 = 'S';
		else if($nNorBallSum >=65 && $nNorBallSum <= 80)
			$strResult5 = 'M';
		else if($nNorBallSum >=81 && $nNorBallSum <= 130)
			$strResult5 = 'L';
		$strSql.= " round_result_5 = '" .$strResult5."', ";

		$strSql.= " round_power = '" .$nPowerball."', ";
		$strSql.= " round_normal = '" .$strNorball."' ";

		//$strSql.= " WHERE round_fid = '".$arrRoundInfo['round_fid']."' ";
		$strSql.= " WHERE round_date = '".$arrRoundInfo['round_date']."' ";
		$strSql.= " AND round_num = '".$arrRoundInfo['round_no']."' ";

		//자료기지 등록
		if ($this->mDbConn->query($strSql) === TRUE) {
			return $arrRoundInfo['round_fid'];
		
		}
		
        return 0;

	}


}


?>