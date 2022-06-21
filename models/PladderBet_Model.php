<?php

class PladderBet_Model {

	private $mDbConn ;
	private $mTableName ;
	private $mMemberTable;

	function __construct($dbConn)
	{
		$this->mDbConn = $dbConn;
		$this->mTableName = "bet_powerladder";
        $this->mMemberTable = "member";

	}



    public function getWaits($arrRoundTm){
    	$strSql = "SELECT * FROM ".$this->mTableName;
    	$strSql.= " WHERE bet_state = '1' ";
    	$strSql.= " AND bet_round_no = '".$arrRoundTm['round_no']."' ";
    	$strSql.= " AND bet_time >= '".$arrRoundTm['round_start']."' ";
    	$strSql.= " AND bet_time <= '".$arrRoundTm['round_end']."' ";

    	$arrResult = array();
    	if($objResult = $this->mDbConn->query($strSql)){
	    	if ($objResult->num_rows > 0) {
			  	while($arrRow = $objResult->fetch_assoc()) {
			    	array_push($arrResult, $arrRow);
		  		}
			}
			$objResult->free();
		}
		return $arrResult;

    }



    function updateBetRound($objRoundInfo, &$objBetInfo)
    {
        $nWinMoney = 0;
        $isWin = false;
        if($objRoundInfo->round_state == 0)
            return false;
        
        //bet_state=2:Betting-loss 3:Betting-Earn 
        switch(intval($objBetInfo->bet_mode)){
            case 1:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1;
                if($objBetInfo->bet_target == $objRoundInfo->round_result_1){
                    $isWin = true;                            
                }
                break;
            case 2:
                $objBetInfo->bet_result = $objRoundInfo->round_result_2;
                if($objBetInfo->bet_target == $objRoundInfo->round_result_2){
                    $isWin = true;
                }
                break;
            case 3:
                $objBetInfo->bet_result = $objRoundInfo->round_result_3;
                if($objBetInfo->bet_target == $objRoundInfo->round_result_3){
                    $isWin = true;
                }
                break;
            case 4:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "P" && $objRoundInfo->round_result_2 == "P"){
                    $isWin = true;
                }
                break;
            case 5:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "P" && $objRoundInfo->round_result_2 == "B"){
                    $isWin = true;
                }
                break;
            case 6:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "B" && $objRoundInfo->round_result_2 == "P"){
                    $isWin = true;
                }
                break;
            case 7:
                $objBetInfo->bet_result = $objRoundInfo->round_result_1.$objRoundInfo->round_result_2;
                if($objRoundInfo->round_result_1 == "B" && $objRoundInfo->round_result_2 == "B"){
                    $isWin = true;
                }
                break;
            default:return false;
        } 

        if($isWin){
            $objBetInfo->bet_state = 3;
            //bet_win_money
            $nWinMoney = $objBetInfo->bet_money * $objBetInfo->bet_ratio;
            $objBetInfo->bet_win_money = (int)$nWinMoney;
            //user_after_money
            $objBetInfo->user_after_money = ($objBetInfo->user_before_money + $objBetInfo->bet_win_money - $objBetInfo->bet_money);
           
        } else {
            $objBetInfo->bet_state = 2;
            $objBetInfo->bet_win_money = 0;
            //user_after_money
            $objBetInfo->user_after_money = ($objBetInfo->user_before_money - $objBetInfo->bet_money);

        }


        $strSql = "UPDATE ".$this->mTableName." SET ";
		$strSql.= " bet_state = '".$objBetInfo->bet_state."', ";	
		$strSql.= " bet_result = '" .$objBetInfo->bet_result."', ";
		$strSql.= " bet_win_money = '" .$objBetInfo->bet_win_money."', ";
		$strSql.= " user_after_money = '" .$objBetInfo->user_after_money."', ";
		$strSql.= " account_time = NOW() ";

		$strSql.= " WHERE bet_fid = '".$objBetInfo->bet_fid."' ";

        return $this->mDbConn->query($strSql);
    }




     function getBetSumByMode($arrRoundInfo, $objConfPs){

        $arrSumData = array();

        for($i = 0; $i <3; $i ++){

            $iMode = $i+1;
            $strSql = " SELECT SUM(bet_money_sum * mb_game_ps_percent DIV 100) AS bet_money_allsum FROM ( ";
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, mb_game_ps_percent FROM ".$this->mTableName;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->mTableName.".bet_mb_uid ";
            $strSql .= " WHERE bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time > '".$arrRoundInfo['round_start']."' AND bet_time < '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='P' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";
            //유저별 배팅결과 합
            $nSum = 0;
            if($objResult = $this->mDbConn->query($strSql)){
                if ($objResult->num_rows > 0) {
                    while($arrRow = $objResult->fetch_assoc()) {
                        if(!is_null($arrRow['bet_money_allsum']))
                            $nSum = $arrRow['bet_money_allsum'];
                    }
                }
                $objResult->free();
            }
            //게임별 누르기율 계산
            $nSum = $nSum * $objConfPs->game_percent_1 / 100;
            array_push($arrSumData, (int)$nSum);

            $strSql = " SELECT SUM(bet_money_sum * mb_game_ps_percent DIV 100) AS bet_money_allsum FROM ( ";
            $strSql .= " SELECT bet_mb_uid, bet_mode, bet_target, bet_ratio, SUM(bet_money) AS bet_money_sum, mb_game_ps_percent FROM ".$this->mTableName;
            $strSql .= " JOIN ".$this->mMemberTable." ON ".$this->mMemberTable.".mb_uid = ".$this->mTableName.".bet_mb_uid ";
            $strSql .= " WHERE bet_round_no='".$arrRoundInfo['round_no']."' AND bet_state = '1' ";
            $strSql .= " AND bet_time > '".$arrRoundInfo['round_start']."' AND bet_time < '".$arrRoundInfo['round_end']."' ";
            $strSql .= " AND bet_mode='".$iMode."' AND bet_target='B' GROUP BY bet_mb_uid ";
            $strSql .= " ) tb_sum ";
            //유저별 배팅결과 합
            $nSum = 0;
            if($objResult = $this->mDbConn->query($strSql)){
                if ($objResult->num_rows > 0) {
                    while($arrRow = $objResult->fetch_assoc()) {
                        if(!is_null($arrRow['bet_money_allsum']))
                            $nSum = $arrRow['bet_money_allsum'];
                    }
                }
                $objResult->free();
            }
            //게임별 누르기율 계산
            $nSum = $nSum * $objConfPs->game_percent_1 / 100;
            array_push($arrSumData, (int)$nSum);
        
        }  


        return $arrSumData;  
    }




}


?>