<?php

class Member_Model {

	private $mDbConn ;
	private $mTableName ;

    private $getFields = ['mb_fid', 'mb_uid', 'mb_level','mb_emp_fid', 'mb_nickname', 
        'mb_money', 'mb_point', 'mb_grade', 'mb_state_active', 
        'mb_game_pb', 'mb_game_ps', 'mb_game_bb', 'mb_game_bs', 'mb_game_cs', 'mb_game_sl', 
        'mb_game_pb_ratio', 'mb_game_pb2_ratio','mb_game_ps_ratio', 'mb_game_bb_ratio', 'mb_game_bb2_ratio', 
        'mb_game_bs_ratio', 'mb_game_cs_ratio', 'mb_game_sl_ratio', 
        'mb_live_id', 'mb_live_uid', 'mb_live_money', 
        'mb_slot_uid', 'mb_slot_money', 
        'mb_fslot_id', 'mb_fslot_uid', 'mb_fslot_money',
        'mb_kgon_id', 'mb_kgon_uid', 'mb_kgon_money',
        'mb_gslot_uid', 'mb_gslot_money', 
    ];
        
	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "member";
	}
    
	public function getByFid($fid){
        $strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE mb_fid = '".$fid."' ";
    	
    	$objMember = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$objMember = (object)$arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}
		return $objMember;
    }

	public function getByUid($strUId){
        $strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE mb_uid = '".$strUId."' ";
    	
    	$objMember = null;
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	$objMember = (object)$arrRow;
			    	break;
		  		}
			}
			$objResult->free();
		}
		return $objMember;
    }


	public function getMembersByLiveUids($arrLiveUid, $iGame){

        $arrMember = []; 
        if(count($arrLiveUid) < 1)
            return $arrMember;

		$whereIn = implode("','", $arrLiveUid);  
        $whereIn = "'".$whereIn."'";
        
        $where = "";
        if($iGame == GAME_CASINO_EVOL){
            $where = " WHERE mb_live_id IN ( ".$whereIn.")"; 
        } else if($iGame == GAME_SLOT_THEPLUS){
            $where = " WHERE mb_slot_uid IN ( ".$whereIn.")"; 
        } else if($iGame == GAME_SLOT_GSPLAY){
            $where = " WHERE mb_fslot_id IN ( ".$whereIn.")";
        } else if($iGame == GAME_SLOT_GOLD){
            $where = " WHERE mb_gslot_uid IN ( ".$whereIn.")";
        } else if($iGame == GAME_CASINO_KGON || $iGame == GAME_SLOT_KGON){
            $where = " WHERE mb_uid IN ( ".$whereIn.")";
        } else if($iGame == GAME_SLOT_STAR){
            $where = " WHERE mb_uid IN ( ".$whereIn.")";
        } else if($iGame == GAME_CASINO_RAVE || $iGame == GAME_SLOT_RAVE){
            $where = " WHERE mb_rave_id IN ( ".$whereIn.")";
        } else if($iGame == GAME_CASINO_TREEM || $iGame == GAME_SLOT_TREEM){
            $where = " WHERE mb_treem_uid IN ( ".$whereIn.")";
        } else if($iGame == GAME_CASINO_SIGMA || $iGame == GAME_SLOT_SIGMA){
            $where = " WHERE mb_sigma_uid IN ( ".$whereIn.")";
        } else return $arrMember; 
        
        $strSql = "SELECT * FROM ".$this->mTableName;
        $strSql.= $where; 

    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	array_push($arrMember, (object)$arrRow);			    	
		  		}
			}
			$objResult->free();
		}

        return $arrMember;		
    }

      
    public function updateAssets(&$objUser, $inMoney , $inPoint = 0, $iChange=-1, $spec=""){

        if(is_null($objUser))
            return false;

        $inMoney = floatval($inMoney);
        $inPoint = floatval($inPoint);

        if($inMoney == 0 && $inPoint == 0)
            return true;
        
        $strSql = "UPDATE ".$this->table." SET ";
        if($inMoney != 0){
            $strSql.= "mb_money = mb_money";
            $strSql.= $inMoney > 0 ? " + ":" ";
            $strSql.= $inMoney;   
            $strSql.= ", mb_change = ".$iChange;
            $strSql.= ", mb_spec = '".$spec."'";
        }
        
        if($inPoint != 0){
            $strSql.= $inMoney != 0 ? " , ":" ";

            $strSql.= "mb_point = mb_point";
            $strSql.= $inPoint > 0 ? " + ":" ";
            $strSql.= $inPoint;
        }

        $strSql.= " WHERE mb_fid=".$objUser->mb_fid;
        if($inMoney < 0){
            $strSql.= " AND mb_money >= ".abs($inMoney);
        }

		return $this->mDbConn->query($strSql);
        
    }

    function updateWinMoney($objBetInfo){

    	$nEarnMoney = 0;
        if($objBetInfo->bet_win_money > 0){
            $nEarnMoney = $objBetInfo->bet_win_money - $objBetInfo->bet_money;
            if($nEarnMoney < 0)
                $nEarnMoney = 0;
        } else return null;
        

        $strSql1 = "SELECT * FROM ".$this->mTableName;
        $strSql1.= " WHERE mb_uid = '".$objBetInfo->bet_mb_uid."' ";

        $strSql2 = "UPDATE ".$this->mTableName." SET ";
        $strSql2.= " mb_money = mb_money+".$objBetInfo->bet_win_money." ";
        $strSql2.= " WHERE mb_uid = '".$objBetInfo->bet_mb_uid."' ";

        $this->mDbConn->begin_transaction();

        try{
            $objResult1 = $this->mDbConn->query($strSql1);
            $objResult2 =$this->mDbConn->query($strSql2);

            $this->mDbConn->commit();

            $objMember = null;
            if($objResult1 && $objResult2){
                if ($objResult1->num_rows > 0) {
                    while($arrRow = $objResult1->fetch_assoc()) {
                        $objMember = (object)$arrRow;
                        break;
                    }
                }
                $objResult1->free();
            }
            return $objMember;

        } catch(mysqli_sql_exception $exception){
            
            $this->mDbConn->rollbalck();            
            //throw $exception;
            return null;
        }
        return null;

    }

    
    
    function addPoint($fid, $point){
        if($fid < 1)
            return false;
        if($point == 0)
            return false;

        $point = $point > 0 ? "+".$point : "".$point;

        $strSql = "UPDATE ".$this->mTableName ;
        $strSql.= " SET mb_point = mb_point ".$point." WHERE mb_fid='".$fid."' ";
        
        return $this->mDbConn->query($strSql);
        
    }

    function addEmployeePoint($arrEmpPoint){
        if(count ($arrEmpPoint) < 1 )
            return false;

        $strSql = "UPDATE ".$this->mTableName ;
        $strSql.= " JOIN (  SELECT ";
        // $fid = array_key_first($arrEmpPoint);
        $fid = key($arrEmpPoint);

        $strSql.= $fid." AS fid, ".$arrEmpPoint[$fid]." AS pt ";
        unset($arrEmpPoint[$fid]);

        foreach ($arrEmpPoint as $fid => $point) {
            $strSql.= " UNION ALL SELECT ".$fid." , ".$point ;
        }

        $strSql.= ") pointTb ON ".$this->mTableName.".mb_fid = pointTb.fid ";
        $strSql.= "SET mb_point = mb_point + pt" ;

        return $this->mDbConn->query($strSql);
    }

    function updateMemberBlank($arrMemBlank){
        if(count ($arrMemBlank) < 1 )
            return false;

        $strSql = "UPDATE ".$this->mTableName ;
        $strSql.= " JOIN (  SELECT ";
        // $fid = array_key_first($arrMemBlank);
        $fid = key($arrMemBlank);

        $strSql.= $fid." AS fid, ".$arrMemBlank[$fid]." AS pt ";
        unset($arrMemBlank[$fid]);

        foreach ($arrMemBlank as $fid => $point) {
            $strSql.= " UNION ALL SELECT ".$fid." , ".$point ;
        }

        $strSql.= ") pointTb ON ".$this->mTableName.".mb_fid = pointTb.fid ";
        $strSql.= "SET mb_blank_current = pt" ;
        
        return $this->mDbConn->query($strSql);
    }

    function updateMemberBetTm($arrMemBet){
        if(count ($arrMemBet) < 1 )
            return false;

        $strSql = "UPDATE ".$this->mTableName ;
        $strSql.= " JOIN (  SELECT ";
        
        $strNow = date("Y-m-d H:i:s");

        $fid = key($arrMemBet);
        if($arrMemBet[$fid] > $strNow)      //if After current time
            $arrMemBet[$fid] = $strNow;

        $strSql.= $fid." AS fid, '".$arrMemBet[$fid]."' AS bet_time ";
        unset($arrMemBet[$fid]);

        foreach ($arrMemBet as $fid => $bet_time) {
            if($bet_time > $strNow)
                $bet_time = $strNow;
    
            $strSql.= " UNION ALL SELECT ".$fid." , '".$bet_time."' " ;
        }

        $strSql.= ") memTb ON ".$this->mTableName.".mb_fid = memTb.fid AND member.mb_time_bet < memTb.bet_time ";
        $strSql.= "SET mb_time_bet = bet_time" ;
        
        return $this->mDbConn->query($strSql);
    }
    
    public function getEmpMemberByFid($fid)
    {
        $strTbColum = " ".implode(", ", $this->getFields);
        $strTbRColum = " r.".implode(", r.", $this->getFields);

        $strSQL = 'WITH RECURSIVE tbmember ('.$strTbColum.') AS';
        $strSQL .= ' ( SELECT '.$strTbColum.' FROM '.$this->mTableName." WHERE mb_fid = '".$fid."'";
        $strSQL .= ' UNION ALL SELECT '.$strTbRColum.' FROM '.$this->mTableName.' r ';
        $strSQL .= ' INNER JOIN tbmember ON r.mb_fid = tbmember.mb_emp_fid )';
        $strSQL .= ' SELECT * FROM tbmember ';
        
        $strSQL .=  " ORDER BY mb_level DESC ";

        
    	$arrResult = array();
    	if($objResult = $this->mDbConn->query($strSQL)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	array_push($arrResult, (object)$arrRow);
		  		}
			}
			$objResult->free();
		}
		return $arrResult;

    }


    function getEmployeeRatio($objMember, $iGame,  $iMode=0){

        $arrRatio = [];
        
        if(is_null($objMember)) return $arrRatio;
        
        $arrMember = $this->getEmpMemberByFid($objMember->mb_fid);
        
        if(is_null($arrMember) || count($arrMember) < 1)
            return $arrRatio;
        
        $cntMem = count($arrMember);
        $member = reset($arrMember);
        if($member->mb_level != LEVEL_COMPANY)
            return $arrRatio;
        
        if($cntMem == 1){
            $ratio = [];
            
            $ratio['rate'] = getRatioByGame($member, $iGame, $iMode);
            
            if($ratio['rate'] > 0){
                $ratio['mb_fid'] = $member->mb_fid;
                $ratio['mb_uid'] = $member->mb_uid;
                $ratio['comp_rate'] = $ratio['rate'];
                $arrRatio[] = $ratio;
            }
    
        } else {
            for($idx = 0 ; $idx < $cntMem-1; $idx ++){
                $fRatio_1 = getRatioByGame($arrMember[$idx], $iGame, $iMode);
                $fRatio_2 = getRatioByGame($arrMember[$idx+1], $iGame, $iMode);
                
                if($fRatio_1 >= 100) return $arrRatio;
                if($fRatio_1 < $fRatio_2) return $arrRatio;
    
                $ratio = [];
                $ratio['rate'] = $fRatio_1 - $fRatio_2;
                
                if($ratio['rate'] > 0){
                    $ratio['mb_fid'] = $arrMember[$idx]->mb_fid;
                    $ratio['mb_uid'] = $arrMember[$idx]->mb_uid;
                    if($idx == 0)
                        $ratio['comp_rate'] = $fRatio_1;
                    $arrRatio[] = $ratio;
                }
            }

            $ratio = [];
            $member = end($arrMember);
            $ratio['rate'] = getRatioByGame($member, $iGame, $iMode);
            
            if($ratio['rate'] > 0){
                $ratio['mb_fid'] = $member->mb_fid;
                $ratio['mb_uid'] = $member->mb_uid;
    
                $arrRatio[] = $ratio;
            }
        }
        
               
        return $arrRatio;
    
    }

}

?>