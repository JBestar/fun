<?php
    function findMemberByFid($arrMember, $fid){

      $findMember = null;
      foreach ($arrMember as $objMember) {
          if($objMember->mb_fid == $fid){
            $findMember = $objMember;
            break;
          }
      }
      return $findMember;
    }

    function findMemberByLiveId($arrMember, $liveId, $gameId){

      $findMember = null;
      foreach ($arrMember as $objMember) {
        if($gameId == GAME_CASINO_EVOL){
          if($objMember->mb_live_id == $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_SLOT_THEPLUS){
          if($objMember->mb_slot_uid === $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_SLOT_GSPLAY){
          if($objMember->mb_fslot_id == $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_SLOT_GOLD){
          if($objMember->mb_gslot_uid === $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_CASINO_KGON || $gameId == GAME_SLOT_KGON 
            || $gameId == GAME_CASINO_STAR || $gameId == GAME_SLOT_STAR ){
          if($objMember->mb_uid == $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_CASINO_RAVE || $gameId == GAME_SLOT_RAVE ){
          if($objMember->mb_rave_id == $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_CASINO_TREEM || $gameId == GAME_SLOT_TREEM ){
          if($objMember->mb_treem_uid == $liveId){
            $findMember = $objMember;
            break;
          }
        } else if($gameId == GAME_CASINO_SIGMA || $gameId == GAME_SLOT_SIGMA ){
          if($objMember->mb_sigma_uid == $liveId){
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
          case GAME_CASINO_STAR: 
          case GAME_CASINO_EVOL: 
          case GAME_CASINO_RAVE: 
          case GAME_CASINO_TREEM: 
          case GAME_CASINO_SIGMA: 
                $fRatio = $objMember->mb_game_cs_ratio;
                break;
          case GAME_BOGLE_BALL: 
                $fRatio = $iMode<5 ? $objMember->mb_game_bb_ratio : $objMember->mb_game_bb2_ratio;
                break;
          case GAME_BOGLE_LADDER: 
                $fRatio = $objMember->mb_game_bs_ratio;
                break;
          case GAME_SLOT_THEPLUS:
          case GAME_SLOT_GSPLAY: 
          case GAME_SLOT_GOLD: 
          case GAME_SLOT_KGON: 
          case GAME_SLOT_STAR: 
          case GAME_SLOT_RAVE: 
          case GAME_SLOT_TREEM: 
          case GAME_SLOT_SIGMA: 
                $fRatio = $objMember->mb_game_sl_ratio;
                break;
          default: break;
      } 
      $fRatio = floatval($fRatio);

      if($fRatio <= 0)
        $fRatio = 0;
      return $fRatio;
    } 
    
    function allMoney($member){
      $nMoney = 0;
      if(is_null($member))
        return $nMoney;

      $nMoney = floatval($member->mb_money) + $member->mb_live_money + $member->mb_slot_money + $member->mb_fslot_money
         + $member->mb_kgon_money + $member->mb_gslot_money + $member->mb_hslot_money + $member->mb_hold_money 
         + $member->mb_rave_money + $member->mb_treem_money + $member->mb_sigma_money;
      return floor($nMoney); // round($nMoney, NUM_POINT_CNT);
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
        if(strcmp($prd->code, $code) == 0)
          return $prd;
      }
      return null;
    }

    function getPrdByName($arrPrd, $name){
      foreach ($arrPrd as $prd) {
        if(strcasecmp($prd->name, $name) == 0)
          return $prd;
      }
      return null;
    }

    function getPrdByKey($arrPrd, $key){
      foreach ($arrPrd as $prd) {
        if(strcasecmp($prd->key, $key) == 0)
          return $prd;
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

      $arrIdx = array("idx"=>"", "fid"=>0, "fid2"=>0);
      
      $arrInfo = explode("#", $idx);
      if(count($arrInfo) >= 1){
        if(strlen(trim($arrInfo[0])) > 10) 
          $arrIdx['idx'] = trim($arrInfo[0]);
      } 
      if(count($arrInfo) >= 2){
        $arrIdx['fid'] = intval($arrInfo[1]);
      }
      if(count($arrInfo) >= 3){
        $arrIdx['fid2'] = intval($arrInfo[2]);
      }
      return $arrIdx;
    }
    
    function getHistory2Date($idx){

      $arrIdx = array("idx"=>"", "idx2"=>"", "fid"=>0, "fid2"=>0);
      
      $arrInfo = explode("#", $idx);
      if(count($arrInfo) >= 1){
        if(strlen(trim($arrInfo[0])) > 10) 
          $arrIdx['idx'] = trim($arrInfo[0]);
      } 
      if(count($arrInfo) >= 2){
        $arrIdx['fid'] = intval($arrInfo[1]);
      }
      if(count($arrInfo) >= 3){
        if(strlen(trim($arrInfo[2])) > 10) 
          $arrIdx['idx2'] = trim($arrInfo[2]);
      }
      if(count($arrInfo) >= 4){
        $arrIdx['fid2'] = intval($arrInfo[3]);
      }
      return $arrIdx;
    }

    function calcEmpPoint($arrRatio, $amount, $dt){
      $arrEmpPoint = [];
      if($amount <= 0)
        return $arrEmpPoint;
				
      foreach($arrRatio as $ratio){
        $ratio['point'] = $ratio['rate'] * $amount / 100.0; // floor($ratio['rate'] * $amount / 100.0);
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
          $nPoint = $ratio['comp_rate'] * $amount / 100.0; //floor($ratio['comp_rate'] * $amount / 100.0);
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

    function generateString($length, $seed)  
    {  
        // $characters  = "0123456789";  
        $characters = "abcdefghijklmnopqrstuvwxyz";  
        // $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";  
        
        $randStr = "";  
        $nmr_loops = $length;  
        
        while ($nmr_loops--)  
        {  
            mt_srand($seed++);
            $randStr .= $characters[mt_rand(0, strlen($characters) - 1)];  
        }  
        
        return $randStr;  
    }  
    
    function generateExtId(){
      $seed = microtime(true); //1709876543.123456 - 16 digits
      
      $randStr = generateString(48, $seed);
      $secs = intval($seed);
      return $randStr.intval($seed).intval(($seed-$secs)*1000000);
    }

?>