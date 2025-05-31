<?php

include_once('models/SlotBet_Model.php');
include_once('models/CasinoBet_Model.php');

include_once('models/ConfSite_Model.php');
include_once('models/SlotPrd_Model.php');
include_once('models/CasinoPrd_Model.php');

include_once('models/Member_Model.php');
include_once('models/Reward_Model.php');

class ServiceLogic
{
	// private $mSnoopy ;

	private $modelSlotBet;
	private $modelCasinoBet;

	private $modelMember;
	private $modelReward;

	private $modelConfSite;
	private $modelSlotPrd;
	
	public $fLog;
	
	function __construct($dbConn, $fLog){
		// $this->mSnoopy = new Snoopy();
		$this->fLog = $fLog;

		$this->modelCasinoBet = new CasinoBet_Model($dbConn);
		$this->modelSlotBet = new SlotBet_Model($dbConn);

		$this->modelConfSite = new ConfSite_Model($dbConn);
		$this->modelSlotPrd = new SlotPrd_Model($dbConn);
		$this->modelCasinoPrd = new CasinoPrd_Model($dbConn);

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
		$gameId = GAME_SLOT_THEPLUS;
		$confId = CONF_API_THEPLUS;
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
		$gameId = GAME_SLOT_THEPLUS;
		$confId = CONF_API_THEPLUS;
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
		$arrMemBet = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			if(!in_array($bet['PlayerID'], $arrLiveUids))
				array_push($arrLiveUids, $bet['PlayerID']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
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
				
				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['Date']);
				if(is_null($betting)){
					$objPrd = getPrd($arrPrd, $bet['ThirdParty']);
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

				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['Date']);
				$betId = 0;
				if(is_null($betting)){
					$objPrd = getPrd($arrPrd, $bet['ThirdParty']);
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

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}

	
	//===========네츄럴 슬롯 게임=================
	
	public function curlFslotBets(&$order) {
		$gameId = GAME_SLOT_GSPLAY;
		$confId = CONF_API_GSPLAY;
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
		$gameId = GAME_SLOT_GSPLAY;
		$confId = CONF_API_GSPLAY;
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
		$arrMemBet = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			if(!in_array($bet['gs_user_id'], $arrLiveUids))
				array_push($arrLiveUids, $bet['gs_user_id']);
		}

		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
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
			$arrMemBet[$objMember->mb_fid] = strToLocal($bet['created_at']);

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
		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
	
		return $bInsert;
	}

	//===========골드슬롯게임================
		
	public function curlGslotBets(){
		$gameId = GAME_SLOT_GOLD;
		$confId = CONF_API_GOLD;
		$logHead = "<GSLOT> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$url = $arrInfo[0]."/get_date_log";
		$arrIdx = getHistoryDate($objConf->conf_idx);
		
        $arrPost['agent_code'] = trim($arrInfo[1]);
        $arrPost['agent_token'] = trim($arrInfo[2]);

		$strStart = "";
		$strEnd = "";

		if(strlen($arrIdx['idx']) > 0){
			$strStart = $arrIdx['idx'];
			$startAt = strtotime($arrIdx['idx']);
		} else {
			$startAt = time();
			$strStart = date("Y-m-d H:i:s", $startAt);     
		}
		$strEnd = date('Y-m-d H:i:s', strtotime("+1 day", $startAt));

		$arrPost['game_type'] = "slot";
		$arrPost['start'] = $strStart;
		$arrPost['end'] = $strEnd;
		$arrPost['page'] = 0;
		$arrPost['length'] = 1000;

        $post = json_encode($arrPost);

		writeLog($this->fLog, $logHead."Request Date=".$strStart."~".$strEnd);
		$this->modelConfSite->updateLastIdx($objConf->conf_id, $strStart."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		$header =  ['Content-Type: application/json',
			'Content-Length: ' . strlen($post),
			'Accept: */*'
		];

		return getCurl($url, $header, $post);
	}

	public function registerGslotBets($response){
		$gameId = GAME_SLOT_GOLD;
		$confId = CONF_API_GOLD;
		$logHead = "<GSLOT> ";

		$arrPrd = $this->modelSlotPrd->getByCat($gameId);
		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($response))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3 || count($arrPrd) < 1)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		$arrBet = null;
		$arrResult = json_decode($response, true);
		// writeLog($this->fLog, $logHead."response=".$response);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			writeLog($this->fLog, $logHead."status-".$arrResult['status']);
			if($arrResult['status'] == 1){
				//"resultCode": "0",
				//"resultMessage": "OK", // Newly Created Id In AAS
				//"data": [],    
				if(array_key_exists('slot', $arrResult))
					$arrBet = $arrResult['slot'];
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

		$lastIdx = "";
		if($nBetCnt == 0){

			$tmNow = time();
			$startAt = strtotime("-1 day", $tmNow);

			if($arrIdx['idx'] < date('Y-m-d H:i:s', $startAt)){
				$lastIdx = date('Y-m-d H:i:s', strtotime("+1 day", strtotime($arrIdx['idx'])));
				writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);
			} 
		}

		$lastFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		foreach ($arrBet as $bet) {
			writeLog($this->fLog, $logHead."type=".$bet['txn_type'].", user=".$bet['user_code'].", bet=".$bet['bet'].", win=".$bet['win'].", txn_id=".$bet['txn_id'].", created_at=".$bet['created_at']);

			if(!in_array($bet['user_code'], $arrLiveUids))
				array_push($arrLiveUids, $bet['user_code']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			$member->ratio = $this->modelMember->getEmployeeRatio($member, $gameId);
		}
		$bInsert = false;
		$rwLastFid = $this->modelReward->getLastFid($gameId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwLastFid);
		if($rwLastFid > FID_OFFSET*3){
			$rwLastFid = $rwLastFid - FID_OFFSET*3;
		}

		foreach ($arrBet as $bet) {

			$lastIdx = date('Y-m-d H:i:s', strtotime("+1 second", strtotime($bet['created_at'])));;

			$bet['provider'] = 0;
			$objMember = findMemberByLiveId($arrMember, $bet['user_code'], $gameId);
			if(is_null($objMember))
				continue;

			$bet['game_id'] = $gameId;
			$betting = $this->modelSlotBet->getByGslot($gameId, $bet, $lastFid);	//베팅내역체크

			if($bet['txn_type'] === "credit")			//청산
			{	
				if(!is_null($betting) &&  intval($betting['bet_win_money']) == $bet['win'])
					continue;
				
				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['created_at']);
				if(is_null($betting)){
					$objPrd = getPrdByName($arrPrd, $bet['provider_code']);
					if(!is_null($objPrd))
						$bet['provider'] = $objPrd->code;
					$betId = $this->modelSlotBet->insertGslot($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert ACCId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateGslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				}
			} else {								//베팅
				if(!is_null($betting) &&  intval($betting['bet_money']) == $bet['bet'])
					continue;
				
				$bBlank = false;
				$nBlankPt = 0;

				$arrEmpRatio = calcEmpPoint($objMember->ratio, $bet['bet'], strToLocal($bet['created_at']));
				if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
					$objMember->mb_blank_current = 0;
					$bBlank = true;
					$nBlankPt = calcCompPoint($objMember->ratio, $bet['bet']);
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

				$arrMemBet[$objMember->mb_fid] = strToLocal($bet['created_at']);
				$betId = 0;
				if(is_null($betting)){
					$objPrd = getPrdByName($arrPrd, $bet['provider_code']);
					if(!is_null($objPrd))
						$bet['provider'] = $objPrd->code;
					$betId = $this->modelSlotBet->insertGslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
				} else {
					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateGslot($betId, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Update BetId=".$betId."=>Result-".$bResult);

				}
					
				if($betId > 0){
					$this->modelReward->insert($gameId, $betId, $arrEmpRatio, $rwLastFid, $bBlank);
					$bInsert = true;
					writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
				}
			}

			// writeLog($this->fLog, $logHead."BET-".$bet['user_code']."=>".$bet['bet']);
		}

		if(strlen($lastIdx) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}

	//===========KGON슬롯게임================
		
	public function curlKgonBets(){
		$confId = CONF_API_KGON;
		$logHead = "<KGON> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$url = $arrInfo[0]."/transaction";
		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		$post = "limmit=2000";
		// if($arrIdx['idx'] == ""){
		// 	$arrIdx['idx'] = date('Y-m-d\TH:i:s', strtotime("-10 hours", time())); 
		// } 
		if($arrIdx['idx'] !== "")
        	$post.= "&sdate=".$arrIdx['idx'];

		$header =  [
			'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($post),
            'Accept: */*',
            'k-username: '.$arrInfo[1],
            'k-secret: '.$arrInfo[2]
		];
        
		writeLog($this->fLog, $logHead."ReqSdate=".$arrIdx['idx']);

		return getCurl($url, $header, $post);
	}

	public function registerKgonBets($response){
		$gameSlotId = GAME_SLOT_KGON;
		$gameCasId = GAME_CASINO_KGON;
		$confId = CONF_API_KGON;
		$logHead = "<KGON> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		// $arrPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		
		if(is_null($response))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		$arrBet = null;
		$arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("code", $arrResult)) {
			if($arrResult['code'] == 0){
				$arrResult['status'] = 1;
				//"code": 0,
                //"transactions": [],    
                $arrBet = $arrResult['transactions'];
            } else { 
				$arrResult['status'] = 0;
				//"code": 0,
                //"msg": ""
            }
		} else {
            $arrResult['status'] = 0;
            
        }

		if(is_null($arrBet)) {			
			return false;
		}

		$arrCasIds = [];
		foreach ($arrCasPrd as $casPrd) {
			array_push($arrCasIds, $casPrd->vendor_id);
		}

		$arrSlotIds = [];
		foreach ($arrSlotPrd as $slotPrd) {
			array_push($arrSlotIds, $slotPrd->code);
		}

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));

		$lastIdx = $arrIdx['idx'];
		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		}
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastSlFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		$slExist = false;
		$csExist = false;
		$logHead = "<KGON> BET ";
		foreach ($arrBet as $bet) {
			writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);

			if($bet['gameCategory'] == "casino")
				$csExist = true;
			else if($bet['gameCategory'] == "slot")
				$slExist = true;
			else {
				if(in_array(strval($bet['vendorId']), $arrCasIds)){
					$bet['gameCategory'] = "casino";
					// writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
					$csExist = true;
				}
				else if(in_array(strval($bet['vendorId']), $arrSlotIds)){
					$bet['gameCategory'] = "slot";
					$slExist = true;
				}
			}
			if(!in_array($bet['siteUsername'], $arrLiveUids))
				array_push($arrLiveUids, $bet['siteUsername']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}

		$lastIdx2 = "";
		$tmNow = time();
		$lastTime2 = date('Y-m-d H:i:s', strtotime("-10 minutes", $tmNow));

		foreach ($arrBet as $bet) {

			$lastIdx = $bet['utcCreatedAt'];
			
			$objMember = findMemberByLiveId($arrMember, $bet['siteUsername'], $gameSlotId);
			if(is_null($objMember)){
				$logHead = "<KGON>";
				writeLog($this->fLog, $logHead."No member-".$bet['gameCategory'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
				continue;
			}

			if(strlen($bet['gameCategory']) == 0){
				if( in_array(strval($bet['vendorId']), $arrCasIds)){
					$bet['gameCategory'] = "casino";
					// writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
				}
				else if(in_array(strval($bet['vendorId']), $arrSlotIds)){
					$bet['gameCategory'] = "slot";
				}
			}
			

			$bet['agent_id'] = $arrInfo[1];			//agent code			
			$bet['user_id'] = $objMember->mb_kgon_id;

			if($bet['gameCategory'] == "casino"){
				$logHead = "<KGON> CAS>> ";
				writeLog($this->fLog, $logHead.$bet['gameCategory'].", vendorId=".$bet['vendorId'].", ".$bet['siteUsername'].", bet-".$bet['refId']."=>".$bet['cash']);
				$bet['txn_id'] = $bet['refId'];
				$betting = $this->modelCasinoBet->getByBet($bet, $lastCsFid);	//베팅내역체크

				if($bet['type'] != "turn_bet")			//청산
				{		
					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found-".$bet['refId']."=>".$bet['cash']);
						continue;
					}

					$bSpec = false;
					$betId = $betting['bet_fid'];
					if(strlen(trim($betting['bet_result'])) > 0 ){
						writeLog($this->fLog, $logHead."ACC-Already-".$bet['refId']."=>".$bet['cash']);

						if($bet['vendorKey'] == "evolution_casino" || $bet['vendorKey'] == "dreamgaming_casino"){
							if(strlen(trim($betting['bet_spec'])) == 0 ){
								$bSpec = $this->modelCasinoBet->updateKSpec($betId, $bet);
								writeLog($this->fLog, $logHead."ACC-Update Spec1-".$bet['refId'].", createdAt=".$bet['createdAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);

								if(!$bSpec && $bet['createdAt'] > $lastTime2 && ( strlen($lastIdx2) == 0 || $lastIdx2 > $bet['utcCreatedAt']) ){
									$lastIdx2 = $bet['utcCreatedAt'];
									writeLog($this->fLog, $logHead."ACC-Update Spec2-".$bet['refId'].", utcCreatedAt=".$bet['utcCreatedAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);
								}
							}
						}
						continue;
					}

					if($bet['vendorKey'] == "evolution_casino" || $bet['vendorKey'] == "dreamgaming_casino"){
						if(strlen(trim($betting['bet_spec'])) == 0 ){
							$bSpec = $this->modelCasinoBet->updateKSpec($betId, $bet);
							writeLog($this->fLog, $logHead."ACC-Update Spec1-".$bet['refId'].", createdAt=".$bet['createdAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);

							if(!$bSpec && $bet['createdAt'] > $lastTime2 && ( strlen($lastIdx2) == 0 || $lastIdx2 > $bet['utcCreatedAt'])){
								$lastIdx2 = $bet['utcCreatedAt'];
								writeLog($this->fLog, $logHead."ACC-Update Spec2-".$bet['refId'].", utcCreatedAt=".$bet['utcCreatedAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);
							}
						}
					} 
					if(!$bSpec)
						$this->modelCasinoBet->updateK($betId, $bet);

					writeLog($this->fLog, $logHead."Update ACCId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];
					
					if($bet['type'] == "turn_draw"){	//타이라면
						
						$arrRewards = $this->modelReward->getByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
						foreach($arrRewards as $objReward){
							writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." mb_fid=".$objReward->rw_mb_fid);

							if(array_key_exists($objReward->rw_mb_fid, $arrEmpPoint )){
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point1 =".$arrEmpPoint[$objReward->rw_mb_fid]);
								$arrEmpPoint[$objReward->rw_mb_fid] -= $objReward->rw_point;
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
							else {
								$arrEmpPoint[$objReward->rw_mb_fid] = 0-$objReward->rw_point;	
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
						}

						$this->modelReward->deleteByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
					}

				} else	//베팅
				{
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['refId']."=>".$bet['cash']);
						continue;
					}
					
					$betId = $this->modelCasinoBet->insertK($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];
					if($betId > 0){
						
						if($bet['vendorKey'] == "evolution_casino" || $bet['vendorKey'] == "dreamgaming_casino"){
							$bSpec = $this->modelCasinoBet->updateKSpec($betId, $bet);
							writeLog($this->fLog, $logHead."BET-INSERT Spec1-".$bet['refId'].", createdAt=".$bet['createdAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);

							if(!$bSpec && $bet['createdAt'] > $lastTime2 && ( strlen($lastIdx2) == 0 || $lastIdx2 > $bet['utcCreatedAt']) ){
								$lastIdx2 = $bet['utcCreatedAt'];
								writeLog($this->fLog, $logHead."BET-INSERT Spec2-".$bet['refId'].", utcCreatedAt=".$bet['utcCreatedAt'].", lastIdx2=".$lastIdx2."=>".$bSpec);
							}
						} 
						
						$arrIdx['fid2'] = $betId;
						$bInsert = true;
						writeLog($this->fLog, $logHead."BET-INSERT-".$bet['type']."-".$bet['_id']."=>".$bet['cash']);

						$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $bet['cash'], $bet['createdAt']);
						foreach($arrEmpRatio as $ratio){
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
					}
					
				}
			} else if($bet['gameCategory'] == "slot"){
				$logHead = "<KGON> SLOT>> ";

				writeLog($this->fLog, $logHead."bet-".$bet['refId']."=>".$bet['cash']);
				$bet['game_id'] = $gameSlotId;
				$betting = $this->modelSlotBet->getByKslot($gameSlotId, $bet, $lastSlFid);	//베팅내역체크
				
				if($bet['type'] != "turn_bet")			//청산
				{	
					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found-".$bet['refId']."=>".$bet['cash']);
						continue;
					}

					if(strlen(trim($betting['bet_result'])) > 0 ){
						writeLog($this->fLog, $logHead."ACC-Already-".$bet['refId']."=>".$bet['cash']);
						continue;
					}
					
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];

					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateKslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				} else {								//베팅
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['refId']."=>".$bet['cash']);
						continue;
					}
					
					$bBlank = false;
					$nBlankPt = 0;

					$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $bet['cash'], $bet['createdAt']);
					if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
						$objMember->mb_blank_current = 0;
						$bBlank = true;
						$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['cash']);
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

					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];
					$betId = $this->modelSlotBet->insertKslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					if($betId > 0)
						$arrIdx['fid'] = $betId;
						
					if($betId > 0){
						$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
						$bInsert = true;
						writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
					}
				}

			} else{
				$logHead = "<KGON>";
				writeLog($this->fLog, $logHead."No Category-".$bet['gameCategory'].", ".$bet['siteUsername'].", ".$bet['refId']."=>".$bet['cash']);
			}
			
		}
		writeLog($this->fLog, "<KGON> END lasdIdx=".$lastIdx." lastIdx2=".$lastIdx2);
		if(strlen($lastIdx2) > 0){

			$tmDt = strtotime($lastIdx2);
			$tmDt = strtotime("-9 hours", $tmDt);
			$tmDt = strtotime("-1 second", $tmDt);

			$lastIdx = date("Y-m-d", $tmDt)."T".date("H:i:s", $tmDt).".000Z";
		}

		if(strlen($lastIdx) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}


	//===========STAR API=================
	
	public function curlStarBets() {
		$confId = CONF_API_STAR;
		$logHead = "<STAR> ";

		$objConf = $this->modelConfSite->getById($confId);
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;
		
		$strStart = "";
		$strEnd = "";
		$arrIdx = getHistoryDate($objConf->conf_idx);

		if(strlen($arrIdx['idx']) > 0){
			$strStart = $arrIdx['idx'];
			$startAt = strtotime($strStart);
			$strEnd = date('Y-m-d H:i:s', strtotime("+1 hour", $startAt));
		} else {
			$tmNow = time();
			$strStart = date("Y-m-d H:i:s", $tmNow);     
			$strEnd = date('Y-m-d H:i:s', strtotime("+1 hour", $tmNow));
		}

		$url = $arrInfo[0]."/BetWinHistory";
		$url.= "?startdate=".$strStart;
		$url.= "&enddate=".$strEnd;
		$url.= "&page=1";

		writeLog($this->fLog, $logHead."Request Date=".$strStart."~".$strEnd);

		$header =  [ 'secret_key: '.$arrInfo[2] ];

		return getCurl($url, $header);
		
	}

	public function registerStarBets($response) {
		$gameSlotId = GAME_SLOT_STAR;
		$gameCasId = GAME_CASINO_STAR;
		$confId = CONF_API_STAR;
		$logHead = "<STAR> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		$arrInfo = explode("#", $objConf->conf_content);
		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		
		if(is_null($response))
			return false;
			
		if(is_null($objConf))
			return false;

		$arrResult = json_decode($response, true);

		$arrBet = null;
		
		if(!is_null($arrResult) && array_key_exists("state", $arrResult)) {
			if($arrResult['state'] == 0){
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
		// writeLog($this->fLog, $logHead."CsPrd Count-".count($arrCasPrd));

		$lastIdx = "";
		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		} 
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastSlFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		$slExist = false;
		$csExist = false;
		foreach ($arrBet as $bet) {
			if($bet['gameType'] != "slot")
				$csExist = true;
			else if($bet['gameType'] == "slot")
				$slExist = true;
			if(!in_array($bet['playerID'], $arrLiveUids))
				array_push($arrLiveUids, $bet['playerID']);
		}

		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."***Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}

		$objPrd = null;
		$cnt = count($arrBet);
		for ($i = $cnt-1; $i>=0 ; $i--) {

			$bet = $arrBet[$i];
			
			$objMember = findMemberByLiveId($arrMember, $bet['playerID'], $gameSlotId);
			if(is_null($objMember))
				continue;

			if($bet['gameType'] != "slot"){
				$logHead = "<STAR> cas>>";
				
				$objPrd = getPrdByKey($arrCasPrd, $bet['providerName']);
				if(is_null($objPrd))
					continue;

				$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['transactionID'], $lastCsFid);	//베팅내역체크

				if(!is_null($betting))
					continue;

				if($lastIdx === "" || $lastIdx < $bet['regdate'])
					$lastIdx = $bet['regdate'];
					
				$bet['agent_id'] = $arrInfo[1];					//agend id
				$bet['prd_id'] = $objPrd->vendor_id;			//provider code
				$arrMemBet[$objMember->mb_fid] = $bet['regdate'];
	
				writeLog($this->fLog, $logHead."BET-".$bet['gameType']." transactionID=".$bet['transactionID']."=> bet=".$bet['bet']." win=".$bet['win']);
	
				$betId = $this->modelCasinoBet->insertH($objMember, $bet);
				if($betId > 0){
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					$arrIdx['fid2'] = $betId;
					$bInsert = true;
	
					if($bet['bet'] > 0 && $bet['bet'] !== $bet['win']){
						$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $bet['bet'], $bet['regdate']);
						foreach($arrEmpRatio as $ratio){
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
			
					}
				}
			} else if($bet['gameType'] == "slot"){
				$logHead = "<STAR> slot>>";
				// writeLog($this->fLog, $logHead."BET-".$bet['transactionID']."=>".$bet['bet']);

				$objPrd = getPrdByName($arrSlotPrd, $bet['providerName']);
				if(is_null($objPrd))
					continue;

				$betting = $this->modelSlotBet->getByHslot($gameSlotId, $bet, $lastSlFid);	//베팅내역체크
				if(!is_null($betting))
					continue;
				
				if($lastIdx === "" || $lastIdx < $bet['regdate'])
					$lastIdx = $bet['regdate'];

				$bet['agent_code'] = $arrInfo[1];		//agend id
				$bet['prd_id'] = $objPrd->code;			//provider code
				$bet['game_id'] = $gameSlotId;				//game id


				$bBlank = false;
				$nBlankPt = 0;

				$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $bet['bet'], $bet['regdate']);
				if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
					$objMember->mb_blank_current = 0;
					$bBlank = true;
					$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['bet_money']);
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
				$bet['game_id'] = $gameSlotId;
				$arrMemBet[$objMember->mb_fid] = $bet['regdate'];

				$betId = $this->modelSlotBet->insertHslot($objMember, $bet, $bBlank, $nBlankPt);
				if($betId > 0){
					$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
					$bInsert = true;
					$arrIdx['fid'] = $betId;
				}
				writeLog($this->fLog, $logHead."Insert BetId=".$betId);
			}
		}

		if(strlen($lastIdx) == 0){
			$tmNow = time();
			$strStart = date("Y-m-d H:i:s", $tmNow);     
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime("-30 minutes", $tmNow));
			
		} else 
			$arrIdx['idx'] = $lastIdx; 

		writeLog($this->fLog, $logHead."lastIdx=".$arrIdx['idx']);

		$this->modelConfSite->updateLastIdx($objConf->conf_id, $arrIdx['idx']."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		// writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);
		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		// writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
	
		return $bInsert;
	}


	//===========Rave게임================
		
	public function curlRaveBets(){
		$confId = CONF_API_RAVE;
		$logHead = "<RAVE> ";

		$objConf = $this->modelConfSite->getById($confId);
		
		if(is_null($objConf))
			return null;
		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return null;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		if($arrIdx['idx'] == ""){
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime("-10 hours", time())); 
		} else {
			$arrIdx['idx'] = date('Y-m-d H:i:s', strtotime($arrIdx['idx']));
		}
		$startAt = $arrIdx['idx'];
		$endAt = date('Y-m-d H:i:s', strtotime("+1 hour", strtotime($arrIdx['idx'])));

		$url = $arrInfo[0]."/bet?";
		$url.="start=".urlencode($startAt);
		$url.="&end=".urlencode($endAt);
		$url.="&pageIdx=0";
		$url.="&pageSize=1000";

		$header =  [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Bearer '.$arrInfo[2]
		];
        
		writeLog($this->fLog, $logHead.$startAt."~".$endAt);
		$this->modelConfSite->updateLastIdx($objConf->conf_id, $startAt."#".$arrIdx['fid']."#".$arrIdx['fid2']);

		return getCurl($url, $header);
	}

	public function registerRaveBets($arrResult){
		$gameSlotId = GAME_SLOT_RAVE;
		$gameCasId = GAME_CASINO_RAVE;
		$confId = CONF_API_RAVE;
		$logHead = "<RAVE> ";

		$arrCasPrd = $this->modelCasinoPrd->getByCat($gameCasId);
		$arrSlotPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		$objConf = $this->modelConfSite->getById($confId);
		
		// $arrPrd = $this->modelSlotPrd->getByCat($gameSlotId);
		
		if(is_null($arrResult))
			return false;

		if(is_null($objConf))
			return false;

		$arrInfo = explode("#", $objConf->conf_content);

		if(count($arrInfo) < 3)	//0-host, 1-ag_code, 2-ag_token
			return false;

		$arrIdx = getHistoryDate($objConf->conf_idx);
		$arrBet = null;
		
		if(!array_key_exists("code", $arrResult)){
			writeLog($this->fLog, $logHead." Error=No Http Code");
		} else if($arrResult['code'] == HTTP_CODE_200){ 
			$arrBet = json_decode($arrResult['body'], true);
		} else {
			writeLog($this->fLog, $logHead." Error=".json_encode($arrResult));
		}

		if(is_null($arrBet)) {			
			return false;
		}

		// $arrCasIds = [];
		// foreach ($arrCasPrd as $casPrd) {
		// 	array_push($arrCasIds, $casPrd->vendor_id);
		// }

		// $arrSlotIds = [];
		// foreach ($arrSlotPrd as $slotPrd) {
		// 	array_push($arrSlotIds, $slotPrd->key);
		// }

		$nBetCnt = count($arrBet);
		writeLog($this->fLog, $logHead."Bet Count-".count($arrBet));
		$lastIdx = "";
		if($nBetCnt == 0){

			$tmNow = time();
			$startAt = strtotime("-10 hours", $tmNow);

			if($arrIdx['idx'] < date('Y-m-d H:i:s', $startAt)){
				$lastIdx = date('Y-m-d H:i:s', strtotime("+1 hour", strtotime($arrIdx['idx'])));
				writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);
			} 
		}

		$lastSlFid = 0;
		if($arrIdx['fid'] > FID_OFFSET){
			$lastSlFid = $arrIdx['fid'] - FID_OFFSET;
		}
		$lastCsFid = 0;
		if($arrIdx['fid2'] > FID_OFFSET){
			$lastCsFid = $arrIdx['fid2'] - FID_OFFSET;
		} 
		writeLog($this->fLog, $logHead."Last Fid-".$lastSlFid);

		$objMember = null;
		$arrEmpPoint = array();
		$arrMemBlank = array();
		$arrMemBet = array();
		
		$arrLiveUids = [];
		$slExist = false;
		$csExist = false;
		$logHead = "<RAVE> BET ";
		foreach ($arrBet as $bet) {
			writeLog($this->fLog, $logHead.$bet['game']['type'].", vendor=".$bet['game']['vendor'].", ".$bet['user']['name'].", ".$bet['id']."(".$bet['refererId'].")=>".$bet['amount']);

			if(strtolower($bet['game']['type']) == "slot")
				$slExist = true;
			else
				$csExist = true;
			
			if(!in_array($bet['user']['id'], $arrLiveUids))
				array_push($arrLiveUids, $bet['user']['id']);
		}
		$arrMember = $this->modelMember->getMembersByLiveUids($arrLiveUids, $gameSlotId);
		if(count($arrMember) > 0)
			writeLog($this->fLog, $logHead."Member Count-".count($arrMember));
		foreach ($arrMember as $member) {
			if($slExist)
				$member->ratio_sl = $this->modelMember->getEmployeeRatio($member, $gameSlotId);
			if($csExist)
				$member->ratio_cs = $this->modelMember->getEmployeeRatio($member, $gameCasId);
		}
		$bInsert = false;
		$rwSlLastFid = $this->modelReward->getLastFid($gameSlotId);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwSlLastFid);
		if($rwSlLastFid > FID_OFFSET*3){
			$rwSlLastFid = $rwSlLastFid - FID_OFFSET*3;
		}

		$rwCsLastFid = $this->modelReward->getLastFid(GAME_CASINO_EVOL);
		writeLog($this->fLog, $logHead."Reward LastFid=".$rwCsLastFid);
		if($rwCsLastFid > FID_OFFSET*3){
			$rwCsLastFid = $rwCsLastFid - FID_OFFSET*3;
		}

		foreach ($arrBet as $bet) {

			$lastIdx = date('Y-m-d H:i:s', strtotime("+1 second", strtotime($bet['createdAt'])));;

			$bet['createdAt'] = date('Y-m-d H:i:s', strtotime("+9 hours", strtotime($bet['createdAt'])));;
			$bet['betAt'] = date('Y-m-d H:i:s', strtotime("+9 hours", strtotime($bet['betAt'])));;

			$objMember = findMemberByLiveId($arrMember, $bet['user']['id'], $gameSlotId);
			if(is_null($objMember)){
				$logHead = "<RAVE>";
				writeLog($this->fLog, $logHead."No member-".$bet['game']['type'].", ".$bet['user']['name'].", ".$bet['id']."(".$bet['refererId'].")=>".$bet['amount']);
				continue;
			}

			$bet['agent_id'] = $arrInfo[1];			//agent code			

			if(strtolower($bet['game']['type']) != "slot"){
				$logHead = "<RAVE> CAS>> ";
				writeLog($this->fLog, $logHead.$bet['game']['type'].", vendor=".$bet['game']['vendor'].", ".$bet['user']['name'].", bet-".$bet['refererId']."=>".$bet['amount']);
				$bet['txn_id'] = $bet['refererId'];
				
				$objPrd = getPrdByKey($arrCasPrd, $bet['game']['vendor']);
				if(is_null($objPrd))
					continue;

				$bet['prd_id'] = $objPrd->vendor_id;			//provider code

				if(strtolower($bet['type']) != "bet")			//청산
				{		
					$betting = null;
					if($bet['refererId'] > 0)
						$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['refererId'], $lastCsFid);	//베팅내역체크
					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}

					$betId = $betting['bet_fid'];
					// if(strlen(trim($betting['bet_result'])) > 0 ){
					if(trim($betting['bet_result']) == $bet['id']){
						writeLog($this->fLog, $logHead."ACC-Already refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}
					// $lastIdx = $createdAtUtc;
					$this->modelCasinoBet->updateR($betId, $bet, $this->fLog);

					writeLog($this->fLog, $logHead."Update ACCId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['betAt'];
					
					if($bet['amount'] == $betting["bet_money"]){	//타이라면
						
						$arrRewards = $this->modelReward->getByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
						foreach($arrRewards as $objReward){
							writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." mb_fid=".$objReward->rw_mb_fid);

							if(array_key_exists($objReward->rw_mb_fid, $arrEmpPoint )){
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point1 =".$arrEmpPoint[$objReward->rw_mb_fid]);
								$arrEmpPoint[$objReward->rw_mb_fid] -= $objReward->rw_point;
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
							else {
								$arrEmpPoint[$objReward->rw_mb_fid] = 0-$objReward->rw_point;	
								// writeLog($this->fLog, $logHead."Cancel RwId=".$objReward->rw_fid." point =".$arrEmpPoint[$objReward->rw_mb_fid]);
							}
						}

						$this->modelReward->deleteByBetId(GAME_CASINO_EVOL, $betId, $rwCsLastFid);
					}

				} else	//베팅
				{
					$betting = $this->modelCasinoBet->getByIdx($objPrd->vendor_id, $bet['id'], $lastCsFid);	//베팅내역체크
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['id']."=>".$bet['amount']);
						continue;
					}
					// $lastIdx = $createdAtUtc;
					
					$betId = $this->modelCasinoBet->insertR($objMember, $bet);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
					$arrMemBet[$objMember->mb_fid] = $bet['betAt'];
					if($betId > 0){
						
						$arrIdx['fid2'] = $betId;
						$bInsert = true;
						writeLog($this->fLog, $logHead."BET-INSERT-".$bet['type']."-".$bet['id']."=>".$bet['amount']);

						$arrEmpRatio = calcEmpPoint($objMember->ratio_cs, $bet['amount'], $bet['betAt']);
						foreach($arrEmpRatio as $ratio){
							if(array_key_exists($ratio['mb_fid'], $arrEmpPoint ))
								$arrEmpPoint[$ratio['mb_fid']] += $ratio['point'];
							else 
								$arrEmpPoint[$ratio['mb_fid']] = $ratio['point'];	
						}
						$this->modelReward->insert(GAME_CASINO_EVOL, $betId, $arrEmpRatio, $rwCsLastFid);
					}
					
				}
			} else {
				$logHead = "<RAVE> SLOT>> ";

				writeLog($this->fLog, $logHead."bet-".$bet['refererId']."=>".$bet['amount']);
				$bet['game_id'] = $gameSlotId;
				$objPrd = getPrdByName($arrSlotPrd, $bet['game']['vendor']);
				if(is_null($objPrd))
					continue;

				$bet['prd_id'] = $objPrd->code;			//provider code
				
				if(strtolower($bet['type']) != "bet")			//청산
				{	
					$betting = null;
					if($bet['refererId'] > 0)
						$betting = $this->modelSlotBet->getByRslot($gameSlotId, $bet['prd_id'], $bet['refererId'], $lastSlFid);	//베팅내역체크

					if(is_null($betting)){
						writeLog($this->fLog, $logHead."ACC-Not Found refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}

					// if(strlen(trim($betting['bet_result'])) > 0 ){
					if(trim($betting['bet_result']) == $bet['id']){
						writeLog($this->fLog, $logHead."ACC-Already refererId=".$bet['refererId']."=>".$bet['amount']);
						continue;
					}
					// $lastIdx = $createdAtUtc;
					$arrMemBet[$objMember->mb_fid] = $bet['createdAt'];

					$betId = $betting['bet_fid'];
					$bResult = $this->modelSlotBet->updateRslot($betId, $bet);
					writeLog($this->fLog, $logHead."Update ACCId=".$betId."=>Result-".$bResult);
				} else {								//베팅
					$betting = $this->modelSlotBet->getByRslot($gameSlotId, $bet['prd_id'], $bet['id'], $lastSlFid);	//베팅내역체크
					if(!is_null($betting)){
						writeLog($this->fLog, $logHead."BET-Exist-".$bet['id']."=>".$bet['amount']);
						continue;
					}
					
					// $lastIdx = $createdAtUtc;
					$bBlank = false;
					$nBlankPt = 0;

					$arrEmpRatio = calcEmpPoint($objMember->ratio_sl, $bet['amount'], $bet['betAt']);
					if($objMember->mb_blank_count > 0 && intval($objMember->mb_blank_current) >= intval($objMember->mb_blank_count)-1 ){
						$objMember->mb_blank_current = 0;
						$bBlank = true;
						$nBlankPt = calcCompPoint($objMember->ratio_sl, $bet['amount']);
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

					$arrMemBet[$objMember->mb_fid] = $bet['betAt'];
						
					$betId = $this->modelSlotBet->insertRslot($objMember, $bet, $bBlank, $nBlankPt);
					writeLog($this->fLog, $logHead."Insert BetId=".$betId);
						
					if($betId > 0){
						$arrIdx['fid'] = $betId;
						$this->modelReward->insert($gameSlotId, $betId, $arrEmpRatio, $rwSlLastFid, $bBlank);
						$bInsert = true;
						writeLog($this->fLog, $logHead."Insert Reward Count=".count($arrEmpRatio));
					}
				}

			} 
			
		}

		if(strlen($lastIdx) > 0)
			$this->modelConfSite->updateLastIdx($objConf->conf_id, $lastIdx."#".$arrIdx['fid']."#".$arrIdx['fid2']);
		writeLog($this->fLog, $logHead."lastIdx=".$lastIdx);

		$this->modelMember->updateMemberBetTm($arrMemBet);
		$bResult = $this->modelMember->updateMemberBlank($arrMemBlank);
		writeLog($this->fLog, $logHead."UpdateMemBlank-Count=".count($arrMemBlank)." Result=".$bResult);
		$bResult = $this->modelMember->addEmployeePoint($arrEmpPoint);
		writeLog($this->fLog, $logHead."AddEmpPoint-Count=".count($arrEmpPoint)." Result=".$bResult);

		return $bInsert;
	}



}


?>
