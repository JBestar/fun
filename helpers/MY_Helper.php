<?php

  //회차번호로부터 회차시작시간과 마감시간, 계산하는 함수-파워볼, 파워사다리
    function getPbRoundTimes($objRoundInfo){
      //date_default_timezone_set('Asia/Seoul');
      
      $nRoundNo = $objRoundInfo->round_num;
      $nSumMinutes = $nRoundNo * 5 ;
      $nHour = $nSumMinutes / 60;
      $nHour = (int)$nHour;
      $nMinute = $nSumMinutes % 60;
      
      $arrRoundInfo['round_no'] =  $objRoundInfo->round_num;
      //날자는 이전회차 날자
      $strNowDate = $objRoundInfo->round_date;
      //회차 시작시간설정      
      $strRoundEnd = $strNowDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      $tmRoundEnd = strtotime("-".TM_OFFSET." seconds", $tmRoundEnd);
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      //회차 시작시간설정
      $tmRoundStart = strtotime("-5 minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo; 
    }
    
      //회차번호로부터 회차시작시간과 마감시간, 계산하는 함수-키노사다리
    function getKsRoundTimes($objRoundInfo){
      //date_default_timezone_set('Asia/Seoul');
      
      $nRoundNo = $objRoundInfo->round_num;
      $nSumMinutes = $nRoundNo * 5 ;
      $nHour = $nSumMinutes / 60;
      $nHour = (int)$nHour;
      $nMinute = $nSumMinutes % 60;
      
      $arrRoundInfo['round_no'] =  $objRoundInfo->round_num;
      //날자는 이전회차 날자
      $strNowDate = $objRoundInfo->round_date;
      //회차 시작시간설정      
      $strRoundEnd = $strNowDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      $tmRoundEnd = strtotime("-".TM_OFFSET." seconds", $tmRoundEnd);
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      //회차 시작시간설정
      $tmRoundStart = strtotime("-5 minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo; 
    }
    
     
    //회차번호로부터 회차시작시간과 마감시간, 계산하는 함수-보글파워볼, 보글사다리
    function getBRoundTimes($objRoundInfo, $roundMin){
      //date_default_timezone_set('Asia/Seoul');
      
      $nRoundNo = $objRoundInfo->round_num;
      $nSumMinutes = $nRoundNo * $roundMin ;
      $nHour = $nSumMinutes / 60;
      $nHour = (int)$nHour;
      $nMinute = $nSumMinutes % 60;
      
      $arrRoundInfo['round_no'] =  $objRoundInfo->round_num;
      //날자는 이전회차 날자
      $strNowDate = $objRoundInfo->round_date;
      //회차 시작시간설정      
      $strRoundEnd = $strNowDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      //회차 시작시간설정
      $tmRoundStart = strtotime("-".$roundMin." minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo; 
    }

    //회차번호, 회차시작시간과 마감시간, 배팅마감시간 계산하는 함수-파워볼, 파워사다리
    function getPbRoundInfo(){

      $tmNow = time()+TM_OFFSET;
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin;
      $nRoundNo = floor($nSumMinutes / 5) ;
      $nRoundNo = $nRoundNo % 288 + 1;
      $arrRoundInfo['round_no'] = $nRoundNo;

      $strDate = "";
      if($nSumMinutes < 1440){
        $strDate = date( 'Y-m-d', $tmNow );
      }
      else {
        $strDate = date('Y-m-d', strtotime("+1 day", $tmNow));
      }

      $arrRoundInfo['round_date'] = $strDate;

      $nSumMinutes = $nRoundNo * 5 ;
      $nHour = $nSumMinutes / 60;
      $nHour = floor($nHour);
      $nMinute = $nSumMinutes % 60;

      //현재시간설정      
      $tmRoundCurrent = date("Y-m-d H:i:s", $tmNow);        
      $arrRoundInfo['round_current'] = $tmRoundCurrent;

      //회차 마감시간설정
      $strRoundEnd = $strDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      
      //회차 시작시간설정
      $tmRoundStart = strtotime("-5 minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo;
    }

    //회차번호, 회차시작시간과 마감시간, 배팅마감시간 계산하는 함수-키노사다리
    function getKsRoundInfo(){

      $tmNow = time()+TM_OFFSET;
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin ;
      $nRoundNo = floor($nSumMinutes / 5) ;
      $nRoundNo = $nRoundNo % 288 + 1;
      $arrRoundInfo['round_no'] = $nRoundNo;

      $strDate = "";
      if($nSumMinutes < 1440){
        $strDate = date( 'Y-m-d', $tmNow );
      }
      else {
        $strDate = date('Y-m-d', strtotime("+1 day", $tmNow));
      }

      $arrRoundInfo['round_date'] = $strDate;

      $nSumMinutes = $nRoundNo * 5 ;
      $nHour = $nSumMinutes / 60;
      $nHour = floor($nHour);
      $nMinute = $nSumMinutes % 60;

      //현재시간설정      
      $tmRoundCurrent = date("Y-m-d H:i:s", $tmNow);        
      $arrRoundInfo['round_current'] = $tmRoundCurrent;

      //회차 마감시간설정
      $strRoundEnd = $strDate." ".$nHour.":".$nMinute.":"."0";
      $tmRoundEnd = strtotime($strRoundEnd);
      $arrRoundInfo['round_end'] = date("Y-m-d H:i:s", $tmRoundEnd);
      
      //회차 시작시간설정
      $tmRoundStart = strtotime("-5 minutes", $tmRoundEnd);
      $arrRoundInfo['round_start'] = date("Y-m-d H:i:s", $tmRoundStart);
      
      return $arrRoundInfo;
    }
    
     //이전회차번호, 날자 계산하는 함수-파워볼, 파워사다리
    function getPbLastRoundInfo(){

      $tmNow = time()+TM_OFFSET;
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin;
      $nRoundNo = floor($nSumMinutes / 5) ;
      $nRoundNo = $nRoundNo % 288 ;
      if($nRoundNo == 0) {
        $nRoundNo = 288;
        $strDate = date('Y-m-d', strtotime("-1 day", $tmNow));
      } else 
        $strDate = date( 'Y-m-d', $tmNow );
      
      $arrRoundInfo['round_no'] = $nRoundNo;
      $arrRoundInfo['round_date'] = $strDate;

      return $arrRoundInfo;
    }

     //이전회차번호, 날자 계산하는 함수-키노사다리
    function getKsLastRoundInfo(){

      $tmNow = time();
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin ;
      $nRoundNo = floor($nSumMinutes / 5) ;
      $nRoundNo = $nRoundNo % 288 ;
      if($nRoundNo == 0) {
        $nRoundNo = 288;
        $strDate = date('Y-m-d', strtotime("-1 day", $tmNow));
      } else 
        $strDate = date( 'Y-m-d', $tmNow );
      

      $arrRoundInfo['round_no'] = $nRoundNo;
      $arrRoundInfo['round_date'] = $strDate;

      return $arrRoundInfo;
    }
    
    //이전회차번호, 날자 계산하는 함수-보글파워볼
    function getBbLastRoundInfo(){

      $tmNow = time();
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin;
      $nRoundNo = floor($nSumMinutes / 2) ;
      $nRoundNo = $nRoundNo % 720 ;
      if($nRoundNo == 0) {
        $nRoundNo = 720;
        $strDate = date('Y-m-d', strtotime("-1 day", $tmNow));
      } else 
        $strDate = date( 'Y-m-d', $tmNow );
      
      $arrRoundInfo['round_no'] = $nRoundNo;
      $arrRoundInfo['round_date'] = $strDate;

      return $arrRoundInfo;
    }

    //이전회차번호, 날자 계산하는 함수-보글사다리
    function getBsLastRoundInfo(){

      $tmNow = time();
      
      $nHour = date("G",$tmNow);
      $nMin = date("i",$tmNow);
      
      $nSumMinutes = $nHour * 60 + $nMin;
      $nRoundNo = floor($nSumMinutes / 3) ;
      $nRoundNo = $nRoundNo % 480 ;
      if($nRoundNo == 0) {
        $nRoundNo = 480;
        $strDate = date('Y-m-d', strtotime("-1 day", $tmNow));
      } else 
        $strDate = date( 'Y-m-d', $tmNow );
      
      $arrRoundInfo['round_no'] = $nRoundNo;
      $arrRoundInfo['round_date'] = $strDate;

      return $arrRoundInfo;
    }

    function getDiffMoney($nMoney1, $nMoney2, $nMin, &$nBetSum){

      $strRes = "";

      if( $nMoney1 - $nMoney2 >= $nMin ){
        $nMoney = $nMoney1 - $nMoney2;
        $nBetSum += $nMoney;
        $strRes .= $nMoney;
        $strRes .= "|0";

      } else if($nMoney2 - $nMoney1 >= $nMin){
        $nMoney = $nMoney2 - $nMoney1;
        $nBetSum += $nMoney;
        $strRes .= "0|";
        $strRes .= $nMoney;     
      } else {
        $strRes .= "0|0";

      }

      return $strRes;
    }

    function getDiffMoney2($nMoney, $nMin, &$nBetSum){
      $strRes = "";
      
      if($nMoney >= $nMin){
        $nBetSum += $nMoney;
        $strRes .= $nMoney;
      } else {
        $strRes .= "0";
      }

      return $strRes;

    } 
    

    function findMemberByLiveId($arrMember, $liveId, $gameId){

      $findMember = null;
      foreach ($arrMember as $objMember) {
        if($gameId == GAME_CASINO_EVOL){
          if($objMember->mb_live_id == $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_SLOT_1){
          if($objMember->mb_slot_uid === $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_SLOT_2){
          if($objMember->mb_fslot_id == $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_CASINO_KGON){
          if($objMember->mb_uid == $liveId){
            $findMember = $objMember;
            break;
          }
        }
        
      }
      return $findMember;
    }
  
    function getRatioByGame($objMember, $iGame, $iMode = 0){
      $fRatio = 0;
      switch($iGame){
          case GAME_POWER_BALL: 
                $fRatio = $iMode<5 ? $objMember->mb_game_pb_ratio : $objMember->mb_game_pb2_ratio;
                break;
          case GAME_POWER_LADDER: 
                $fRatio = $objMember->mb_game_ps_ratio;
                break;
          case GAME_CASINO_KGON: 
          case GAME_CASINO_EVOL: 
                $fRatio = $objMember->mb_game_cs_ratio;
                break;
          case GAME_BOGLE_BALL: 
                $fRatio = $iMode<5 ? $objMember->mb_game_bb_ratio : $objMember->mb_game_bb2_ratio;
                break;
          case GAME_BOGLE_LADDER: 
                $fRatio = $objMember->mb_game_bs_ratio;
                break;
          case GAME_SLOT_1:
          case GAME_SLOT_2: 
                $fRatio = $objMember->mb_game_sl_ratio;
                break;
          default: break;
      } 
      $fRatio = floatval($fRatio);

      if($fRatio <= 0)
        $fRatio = 0;
      return $fRatio;
    } 

    
    function writeLog($fLog, $tContent, $bTm = true){
      
      if($bTm){
        $tmNow = time() ;
        $nHour = date("G",$tmNow);
        $nMin = date("i",$tmNow);
        $nSec = date("s",$tmNow);
        $tContent = "[".$nHour.":".$nMin.":".$nSec."] ".$tContent."\r\n";
      }
      
      echo $tContent;
      if($fLog)
       fputs($fLog, $tContent);
    }

    function getPrd($arrPrd, $code){
      foreach ($arrPrd as $prd) {
        if($prd->code === $code){
          return $prd;
        }
      }
      return null;
    }

    function getHistoryIdx($idx){

      $arrIdx = array("idx"=>0, "fid"=>0);
      
      $arrInfo = explode("#", $idx);
      if(count($arrInfo) >= 1){
        $arrIdx['idx'] = intval($arrInfo[0]);
      } 
      if(count($arrInfo) >= 2){
        $arrIdx['fid'] = intval($arrInfo[1]);
      }
      return $arrIdx;
    }

    function getHistoryDate($idx){

      $arrIdx = array("idx"=>"", "fid"=>0);
      
      $arrInfo = explode("#", $idx);
      if(count($arrInfo) >= 1){
        if(strlen(trim($arrInfo[0])) > 10) 
          $arrIdx['idx'] = trim($arrInfo[0]);
      } 
      if(count($arrInfo) >= 2){
        $arrIdx['fid'] = intval($arrInfo[1]);
      }
      return $arrIdx;
    }
    function calcEmpPoint($arrRatio, $amount, $dt){
      $arrEmpPoint = [];
      if($amount <= 0)
        return $arrEmpPoint;
				
      foreach($arrRatio as $ratio){
        $ratio['point'] = floor($ratio['rate'] * $amount / 100.0);
        if($ratio['point'] > 0){
          $ratio['time'] = $dt;
          $arrEmpPoint[] = $ratio;
        }
      }
      return $arrEmpPoint;

    }

    function calcCompPoint($arrRatio, $amount){
      $nPoint = 0;
      if($amount <= 0)
        return $nPoint;
				
      foreach($arrRatio as $ratio){
        if(array_key_exists('comp_rate', $ratio)){
          $nPoint = floor($ratio['comp_rate'] * $amount / 100.0);
          break;
        }
      }
      return $nPoint;

    }

    function utcToLocal($strDt){
      
      $tmDt = strtotime($strDt);		//UTC "2022-03-15 08:30:27"
		  $tmDt = strtotime("+9 hours", $tmDt);
      return date("Y-m-d H:i:s", $tmDt);
    }
    
    function strToLocal($strDt){
      
      $tmDt = strtotime($strDt);		//UTC "2022-04-24T17:05:34.000Z"
      return date("Y-m-d H:i:s", $tmDt);
    }

?>