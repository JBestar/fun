<?php

include_once('models/SlotBet_Model.php');

include_once('models/ConfSite_Model.php');
include_once('models/SlotPrd_Model.php');

include_once('models/Member_Model.php');
include_once('models/Reward_Model.php');

class ServiceLogic
{
	private $mSnoopy ;

	private $modelSlotBet;

	private $modelMember;
	private $modelReward;

	private $modelConfSite;
	private $modelSlotPrd;
	
	public $fLog;
	
	function __construct($dbConn, $fLog){
		$this->mSnoopy = new Snoopy();
		$this->fLog = $fLog;

		$this->modelSlotBet = new SlotBet_Model($dbConn);

		$this->modelConfSite = new ConfSite_Model($dbConn);
		$this->modelSlotPrd = new SlotPrd_Model($dbConn);

		$this->modelMember = new Member_Model($dbConn);
		$this->modelReward = new Reward_Model($dbConn);
	}


	//배팅사이트 정보얻기
	public function getSiteConf($confId)
	{		
		//게임배팅시간
		$objConfig = $this->modelConfSite->getById($confId);
		if(!is_null($objConfig) && $objConfig->conf_active > 0){
			return true;
		}
		return false;
		
	}
	
	//===========슬롯게임================
	
	public function curlSlotBets(){
		$gameId = GAME_SLOT_1;
		$confId = CONF_SLOT_1;
		$logHead = "<SLOT> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$url = $arrInfo[0]."/system/api/GetBetWinLogByIndex";
		$arrIdx = getHistoryIdx($objConf->conf_idx);
		
		$arrPost['key'] = $arrInfo[1];
		$arrPost['secret'] = $arrInfo[2];
		$arrPost["pageSize"] = 500;
		$arrPost["objectID"] = $arrIdx['idx'] > 0 ? $arrIdx['idx'] + 1 : 0;
        
        $post = json_encode($arrPost);

		writeLog($this->fLog, $logHead."Request ID=".$arrPost["objectID"]);

		$header =  ['Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            'Accept: */*'
		];

		return getCurl($url, $header, $post);
	}

	public function registerSlotBets($response){
		$gameId = GAME_SLOT_1;
		$confId = CONF_SLOT_1;
		$logHead = "<SLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($response))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryIdx($objConf->conf_idx);
		
		$arrBet = null;
		$arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("resultCode", $arrResult)) {
			// writeLog($this->fLog, $logHead."resultCode-".$arrResult['resultCode']);
			if($arrResult['resultCode'] == 0){
                //"resultCode": "0",
                //"resultMessage": "OK", // Newly Created Id In AAS
                //"data": [],    
                $arrBet = $arrResult['data'];
            } else { 
                //"resultCode": "0",
            }
		} else {
        }

		if(is_null($arrBet)) {			
			return false;
		}

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));

		$lastIdx = -1;
		if(array_key_exists("lastObjectID", $arrResult) && $nBetCnt > 0)
			$lastIdx = $arrResult['lastObjectID'];

		$lastFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			if(!in_array($bet['PlayerID'], $arrLiveUids))
				array_push($arrLiveUids, $bet['PlayerID']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		writeLog($this->fLog, $logHead."Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			$member->ratio = $this->modelMember->getEmployeeRatio($member, $gameId);
		}
		$bInsert = false;
		$rwLastFid = $this->modelReward->getLastFid($gameId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwLastFid);
		if($rwLastFid > FID_OFFSET){
			$rwLastFid = $rwLastFid - FID_OFFSET;
		}

		foreach ($arrBet as $bet) {

			$objMember = findMemberByLiveId($arrMember, $bet['PlayerID'], $gameId);
			if(is_null($objMember))
				continue;

			$bet['game_id'] = $gameId;
			$betting = $this->modelSlotBet->getByXslot($gameId, $bet, $lastFid);	//베팅내역체크

			if($bet['Status'] !== "BET")			//청산
			{	
				if(!is_null($betting) &&  intval($betting['bet_win_money']) == $bet['Amount'])
					continue;
	
				if(is_null($betting)){
					$objPrd = getPrd($arrPrd, $bet['ThirdParty']);
					if(!is_null($objPrd))
						$bet['GameID'] = $objPrd->spec."_".$bet['GameID'];
					$betId = $this->modelSlotBet->insertXslot($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert ACCId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateXslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				}
			} else {								//베팅
				if(!is_null($betting) &&  intval($betting['bet_money']) == $bet['Amount'])
					continue;
				
				$bBlank = false;
				$nBlankPt = 0;

				$arrEmpRatio = calcEmpPoint($objMember->ratio, $bet['Amount'], strToLocal($bet['Date']));
				if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
					$objMember->mb_blank_current = 0;
					$bBlank = true;
					$nBlankPt = calcCompPoint($objMember->ratio, $bet['Amount']);
					$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
					writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
				} else {
					foreach($arrEmpRatio as $ratio){
						
						if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
							$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
						else 
							$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
					}
					if($objMember->mb_blank_count > 0){
						$objMember->mb_blank_current ++;
						$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
						writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
					}
					
				}
				
				$betId = 0;
				if(is_null($betting)){
					$objPrd = getPrd($arrPrd, $bet['ThirdParty']);
					if(!is_null($objPrd))
						$bet['GameID'] = $objPrd->spec."_".$bet['GameID'];
					$betId = $this->modelSlotBet->insertXslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateXslot($betId, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Update BetId=".$betId."=>Result-".$bResult);

				}
					
				if($betId > 0){
					$this->modelReward->insert($gameId, $betId, $arrEmpRatio, $rwLastFid, $bBlank);
					$bInsert = true;
					writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
				}
			}

			writeLog($this->fLog, $logHead."BET-".$bet['PlayerID']."=>".$bet['Amount']);
		}

		if($lastIdx > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']);

		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);

		return $bInsert;
	}

	
	//===========네츄럴 슬롯 게임=================
	
	public function curlFslotBets(&$order) {
		$gameId = GAME_SLOT_2;
		$confId = CONF_SLOT_2;
		$logHead = "<FSLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$order ++ ;
		if($order > count($arrPrd) )
			$order = 1;
		$objPrd = $arrPrd[$order-1];			//Prd얻기
		
		$url = $arrInfo[0]."/game/history_with_id";
		$arrIdx = getHistoryIdx($objConf->conf_idx);
		if($objPrd->history_id > 0)
			$arrIdx['idx'] = $objPrd->history_id;

		$arrPost['prd_id'] = $objPrd->code;			//게임사코드
		$arrPost['history_id'] = $arrIdx['idx'] > 0 ? $arrIdx['idx'] + 1 : 0;		//마지막 history_id
        $arrPost['offset'] = "0";
        $arrPost['limit'] = "100";
        $post = json_encode($arrPost);

		writeLog($this->fLog, $logHead."Request PRD=".$arrPost['prd_id']." ID=".$arrPost['history_id']);

		$header =  [
			'Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            'Accept: */*',
            'ag-code: '.$arrInfo[1],
            'ag-token: '.$arrInfo[2]
		];

		return getCurl($url, $header, $post);
		
	}

	public function registerFslotBets($response, $order) {
		$gameId = GAME_SLOT_2;
		$confId = CONF_SLOT_2;
		$logHead = "<FSLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$objPrd = $arrPrd[$order-1];			//Prd얻기		
		$arrIdx = getHistoryIdx($objConf->conf_idx);
		
		$arrResult = json_decode($response, true);

		$arrBet = null;
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                //"status": 1,
                //"total": 1, // Newly Created Id In AAS
                //"data": [],    
                $arrBet = $arrResult['data'];
            } else { 
                //"status": 0,
                //"error": "INVALID_ACCESS_TOKEN", DOUBLE_USER, INSUFFICIENT_FUNDS, INTERNAL_ERROR
            }
		} else {
            $arrResult['status'] = 0;
            
        }

		if(is_null($arrBet)) {			
			return false;
		}

		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));

		$lastIdx = 0;
		$lastFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			if(!in_array($bet['gs_user_id'], $arrLiveUids))
				array_push($arrLiveUids, $bet['gs_user_id']);
		}

		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		writeLog($this->fLog, $logHead."Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			$member->ratio = $this->modelMember->getEmployeeRatio($member, $gameId);
		}
		$bInsert = false;
		$rwLastFid = $this->modelReward->getLastFid($gameId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwLastFid);
		if($rwLastFid > FID_OFFSET){
			$rwLastFid = $rwLastFid - FID_OFFSET;
		}

		foreach ($arrBet as $bet) {

			$lastIdx = $bet['history_id'];
			
			$objMember = findMemberByLiveId($arrMember, $bet['gs_user_id'], $gameId);
			if(is_null($objMember))
				continue;
			
			writeLog($this->fLog, $logHead."BET-".$bet['history_id']."=>".$bet['bet_money']);

			// if($bet['bet_money'] == 0)
			// 	continue;

			$betting = $this->modelSlotBet->getByFslot($gameId, $bet, $lastFid);	//베팅내역체크
			if(!is_null($betting))
				continue;

			writeLog($this->fLog, $logHead."BET-FIND-".$bet['history_id']."=>".$bet['bet_money']);
			
			$bBlank = false;
			$nBlankPt = 0;

			$arrEmpRatio = calcEmpPoint($objMember->ratio, $bet['bet_money'], strToLocal($bet['created_at']));
			if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
				$objMember->mb_blank_current = 0;
				$bBlank = true;
				$nBlankPt = calcCompPoint($objMember->ratio, $bet['bet_money']);
				$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
				writeLog($this->fLog, $logHead."Blank Cross Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current." Comp=".$nBlankPt);
			} else {
				foreach($arrEmpRatio as $ratio){
					
					if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
						$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
					else 
						$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
				}
				if($objMember->mb_blank_count > 0){
					$objMember->mb_blank_current ++;
					$arrMemBlank[$objMember->mb_fid] = $objMember->mb_blank_current;
					writeLog($this->fLog, $logHead."Blank Id=".$objMember->mb_uid." Current=".$objMember->mb_blank_current);
				}
			}
			$bet['game_id'] = $gameId;
			$bet['prd_id'] = $objPrd->code;

			$betId = $this->modelSlotBet->insertFslot($objMember, $bet, $bBlank, $nBlankPt);
			if($betId > 0){
				$this->modelReward->insert($gameId, $betId, $arrEmpRatio, $rwLastFid, $bBlank);
				$bInsert = true;
				$arrIdx['fid'] = $betId;
			}
			writeLog($this->fLog, $logHead."Insert BetId=".$betId);
			
		}

		if($lastIdx > 0){
			$this->modelSlotPrd->updateHistoryId($objPrd->cat, $objPrd->code, $lastIdx);
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $arrIdx['idx']."#".$arrIdx['fid']);
		}

		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
	
		return $bInsert;
	}




}


?>
