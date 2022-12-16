<?php
    

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
        } else if($gameId == GAME_SLOT_3){
          if($objMember->mb_gslot_uid === $liveId){
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
          case GAME_SLOT_3: 
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

    function getPrdByName($arrPrd, $name){
      foreach ($arrPrd as $prd) {
        if($prd->name === $name){
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