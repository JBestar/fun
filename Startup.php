<?php

	include_once('libraries/Snoopy.php');
	include_once('helpers/Constant.php');
	include_once('helpers/Logic_Helper.php');
	include_once('helpers/MY_Helper.php');
	include_once('ServiceLogic.php');
	
	//서버가 기동할 동안 대기
	sleep(1);

	date_default_timezone_set('Asia/Seoul');

	$arrConfig = parse_ini_file("config/config.ini");

	//자료기지 접속
	$dbConn = connectDb($arrConfig);

	if ($dbConn->connect_error) {
	    echo "Connection failed.". $dbConn->connect_error;
	    sleep(50);
	    die("Connection failed: ");
	} 

	$appType = APPTYPE_1;
	if(array_key_exists('app_type', $arrConfig)){
		$appType=intval($arrConfig['app_type']);
	}

	$tRootDir = dirname(__FILE__);
	
	if(!is_dir($tRootDir."/log")){
		mkdir($tRootDir."/log");
	}
	
    $fName = date( 'Y-m-d', time());
	$fLog = fopen($tRootDir."/log/acc_".$fName, "a") ;
	sleep(1);

	$objServLogic = new ServiceLogic($dbConn, $fLog);
	//정산상태 
	$bSlDeny = $objServLogic->getSiteConf(CONF_SLOT_DENY);
	
	$hSlot = null;
	$hFslot = null;
	$bSlReg = false; 
	$bFslReg = false; 

	$ordFsl = 0;
	$logHead = "<Oive>";
	
	$secSleep = 5;
	$secRepeat = 1;

	writeLog($fLog, $logHead."==============START==============");


	while(true){

		$tmNow = time();
		$nHour = date("G",$tmNow);
		$nMin = date("i",$tmNow);
		$nSec = date("s",$tmNow);

		//로그파일 
		if($nHour == 0 && $nMin == 0 && $nSec < 5){

			$strDate = date( 'Y-m-d', $tmNow );
			if($fName !== $strDate){
				if($fLog) fclose($fLog);
				
				$fName = $strDate;
				$fLog = fopen($tRootDir."/log/acc_".$fName, "a") ;
		
				$objServLogic->fLog = $fLog;
				writeLog($fLog, $logHead."Log File--".$fName."");
			}
		}
		
		$nMinSum = $nHour*60 + $nMin;
		$nSecSum = ($nMin%10)*60 + $nSec;
		
		$bInsert = false;
				
		//슬롯1 게임결과 기록
		if(!$bSlDeny && !$bSlReg){
			if($appType == APPTYPE_1 || $appType == APPTYPE_3 || $appType == APPTYPE_4){
				if($hSlot == null){
					$hSlot = curl_multi_init();
					$curl = $objServLogic->curlSlotBets();
					curl_multi_add_handle($hSlot, $curl);
					writeLog($fLog, $logHead."SLOT-REQ-".$hSlot);
				}
				$result = curlProc($hSlot, $fLog );
				if($result != null){
					$bSlReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerSlotBets($result);
				}
			}
			
		}  
		
		//슬롯2 게임결과 기록
		if(!$bSlDeny && !$bFslReg){
			if($appType == APPTYPE_4 || $appType == APPTYPE_5){
				if($hFslot == null){
					$hFslot = curl_multi_init();
					$curl = $objServLogic->curlGslotBets();
					curl_multi_add_handle($hFslot, $curl);
					writeLog($fLog, $logHead."GSLOT-REQ-".$hFslot);
				}
				$result = curlProc($hFslot, $fLog );
				if($result != null){
					$bFslReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerGslotBets($result);
				}
			} else {
				if($hFslot == null){
					$hFslot = curl_multi_init();
					$curl = $objServLogic->curlFslotBets($ordFsl);
					curl_multi_add_handle($hFslot, $curl);
					writeLog($fLog, $logHead."FSLOT-REQ-".$hFslot);
				}
				$result = curlProc($hFslot, $fLog );
				if($result != null){
					$bFslReg = true;
					// writeLog($fLog, $result);
					$bInsert = $objServLogic->registerFslotBets($result, $ordFsl);
				}
			}
			
		}  
		
		
		if($hSlot == null && $hFslot == null){
			$bSlReg = false;
			$bFslReg = false;

			if(!$bInsert)
				sleep($secSleep);
			else sleep($secRepeat);	//usleep(500000);
		}
		
	}
	
	sleep(1000);
	
?>