<?php namespace App\Controllers;

use App\Models\MoneyHist_Model;
use App\Models\Reward_Model;

use App\Models\Round_Model;
use App\Models\Bet_Model;

class ApiSite extends BaseController
{
    private $modelMoneyhist;
    private $modelReward;
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

        header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		
        $this->modelMoneyhist = new MoneyHist_Model();
        $this->modelReward = new Reward_Model();
    }

    public function apibet(){
        $result = new \StdClass;

        //apibet?game=1&id=test&pwd=1234&balance=0|0|0|0|0|0|0|0|0|0|0
        $arrData['game'] = intval($this->request->getVar('game'));
        $arrData['id'] = trim($this->request->getVar('id'));
        $arrData['pwd'] = trim($this->request->getVar('pwd'));
        $arrData['balance'] = trim($this->request->getVar('balance'));

        $result->status = STATUS_FAIL;
        $result->code = 0;

        $objUser = $this->modelMember->login($arrData['id'], $arrData['pwd']);
        if(is_null($objUser)){
            $result->status = STATUS_FAIL;
            $result->code = 1;
        } else if( diffDt(date('Y-m-d H:i:s'), $objUser->mb_time_call) < DELAY_APIBET ) {
            $result->status = STATUS_FAIL;
            $result->code = 8;
        } else if(!checkApiUri($arrData)){
            $result->status = STATUS_FAIL;
            $result->code = RESULT_FAIL;
		} else if($this->modelConfsite->IsMaintain() || !$this->modelMember->isPermitMember($objUser)){
            $result->status = STATUS_FAIL;
            $result->code = RESULT_STOP;	

        } else {
            $this->modelMember->updateCallTm($objUser);
			$this->modelConfsite->readBetConf();

            $objUser->emp_state_active = STATE_ACTIVE;
            $objConfig = null;
            if($arrData['game'] == 1)
			    $objConfig = $this->modelConfgame->find(GAME_HAPPY_BALL);
            // else if($arrData['game'] == 2)
            //     $objConfig = $this->modelConfgame->find(GAME_POWER_LADDER);
            else if($arrData['game'] == 3)
                $objConfig = $this->modelConfgame->find(GAME_BOGLE_BALL);
            else if($arrData['game'] == 4)
                $objConfig = $this->modelConfgame->find(GAME_BOGLE_LADDER);
            else if($arrData['game'] == 5)
                $objConfig = $this->modelConfgame->find(GAME_EOS5_BALL);
            else if($arrData['game'] == 6)
                $objConfig = $this->modelConfgame->find(GAME_EOS3_BALL);
            else if($arrData['game'] == 7)
                $objConfig = $this->modelConfgame->find(GAME_COIN5_BALL);
            else if($arrData['game'] == 8)
                $objConfig = $this->modelConfgame->find(GAME_COIN3_BALL);
        
            
            $arrBalance = checkApiBalance($arrData['balance']);
            $fRate = 100 ; //floatval($arrData['rate']);

            $iResult = 0;
            $iSubResult = 0;

            $modelBet = new Bet_Model();
            $modelRound = new Round_Model();				

			if(is_null($objConfig)){
				$iSubResult = 9;
            } else if(count($arrBalance) < 1){
				$iSubResult = 2;
			} else if($arrData['game'] == 1 || $arrData['game'] == 3 
                || $arrData['game'] == 5 || $arrData['game'] == 6
                || $arrData['game'] == 7 || $arrData['game'] == 8
                ) {	                                    //보글파워볼, EOS파워볼, 코인파워볼 일회차 베팅
                if($arrData['game'] == 1){                  //Happy ball
                    $arrBetData['game'] = GAME_HAPPY_BALL;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);
				
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig, false);
                    $iMoneyType = MONEYCHANGE_BET_PB;
    
                    // if(InvalidGameTime()) {
                    //     $objUser->emp_state_active = STATE_DISABLE;
                    // }
                } else if($arrData['game'] == 3){           //보글파워볼
                    $arrBetData['game'] = GAME_BOGLE_BALL;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);
                    
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBbRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_BB;
                } else if($arrData['game'] == 5){           //EOS5분파워볼
                    $arrBetData['game'] = GAME_EOS5_BALL;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);
				
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig, false);
                    $iMoneyType = MONEYCHANGE_BET_EO5;
                } else if($arrData['game'] == 6){           //EOS3분파워볼
                    $arrBetData['game'] = GAME_EOS3_BALL;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);

                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBsRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_EO3;
                } else if($arrData['game'] == 7){           //Coin5분파워볼
                    $arrBetData['game'] = GAME_COIN5_BALL;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);
				
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig, false);
                    $iMoneyType = MONEYCHANGE_BET_CO5;
                } else if($arrData['game'] == 8){           //Coin3분파워볼
                    $arrBetData['game'] = GAME_COIN3_BALL;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);

                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBsRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_CO3;
                } 
                
                $arrBetData['roundno'] = $arrRoundData['round_no'];
				$arrBetData['roundid'] = count($arrRoundInfo) == 0 ? 1 : (int)($arrRoundInfo[0]->round_fid) + 1;
				
                //회차유효시간
				if(!isEnableBetTime($arrRoundData)) {
					$objUser->emp_state_active = STATE_DISABLE;
				}

                if(count($arrBalance) >= 8 ){
                    for($i = 0; $i <8; $i ++){
                        $iMode = (int)floor($i/2) + 1;
                        
                        $arrBetData['mode'] = $iMode;
                        $arrBetData['target'] = $i%2==0? "P":"B";
                        $arrBetData['amount'] = $arrBalance[$i];
                        
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                        
                    }

                    $nCount = count($arrBalance);
                    if($nCount > 51)
                        $nCount = 51;
                    //조합
                    for($i = 8; $i <$nCount; $i ++){
                        $iMode = $i - 3;
                        if($iMode >= 30 && $iMode <= 37){
                            $arrBetData['mode'] = $iMode+1;
                            $arrBetData['target'] = "R";
                        }
                        else if($iMode >= 38 && $iMode <= 47){
                            $arrBetData['mode'] = 30;
                            $arrBetData['target'] = $iMode-38;
                        }
                        else{
                            $arrBetData['mode'] = $iMode;
                            $arrBetData['target'] = "R";
                        }
                        
                        $arrBetData['amount'] = $arrBalance[$i];
                        $arrBetData['amount'] = (int)round($arrBetData['amount'] * $fRate / 100);
                         
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                        
                    }
                }

				
			} else if(/*$arrData['game'] == 2 ||*/ $arrData['game'] == 4 ){				
				if($arrData['game'] == 2){                      //파워사다리
                    $arrBetData['game'] = GAME_POWER_LADDER;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);

                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getPbRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_PS;
                    if(InvalidGameTime()) {
                        $objUser->emp_state_active = STATE_DISABLE;
                    }
                } else if($arrData['game'] == 4){				//보글사다리
                    $arrBetData['game'] = GAME_BOGLE_LADDER;
                    $modelBet->setType($arrBetData['game']);
				    $modelRound->setType($arrBetData['game']);
                    
                    $arrRoundInfo = $modelRound->gets(1);
                    $arrRoundData = getBsRoundTimes($objConfig);
                    $iMoneyType = MONEYCHANGE_BET_BS;
    
                }    
                
                $arrBetData['roundno'] = $arrRoundData['round_no'];
				$arrBetData['roundid'] = count($arrRoundInfo) == 0 ? 1 : (int)($arrRoundInfo[0]->round_fid) + 1;
                //회차점검시간
				if(!isEnableBetTime($arrRoundData)) {
					$objUser->emp_state_active = STATE_DISABLE;
				}

                if(count($arrBalance) >= 6 ){
                    for($i = 0; $i <6; $i ++){
                        $iMode = (int)floor($i/2) + 1;
                        
                        $arrBetData['mode'] = $iMode;
                        $arrBetData['target'] = $i%2==0? "P":"B";
                        $arrBetData['amount'] = $arrBalance[$i];
                        
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                    }

                    $nCount = count($arrBalance);
                    if($nCount > 10)
                        $nCount = 10;
                    //조합
                    for($i = 6; $i <$nCount; $i ++){
                        $iMode = $i - 2;
                        $arrBetData['mode'] = $iMode;
                        $arrBetData['target'] = "R";
                        $arrBetData['amount'] = $arrBalance[$i];
                        $arrBetData['amount'] = (int)round($arrBetData['amount'] * $fRate / 100);
                         
                        if($arrBetData['amount'] < 1)
                            continue;

                        $iBetResult = $this->apiGameBet($arrBetData, $objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType);
                        if($iBetResult == 1){
                            $iResult = 1;
                            $objUser->mb_money -= $arrBetData['amount'];
                        }
                        else {
                            if($iSubResult == 0)
                                $iSubResult = $iBetResult;
                        }
                    }
                }
			
			} else {
				$result->asdf = $arrData['game'];

                $iSubResult = 9;           //존재하지 않는 게임
            }				

            $objUser = $this->modelMember->getByUid($objUser->mb_uid);
            $result->money = intval($objUser->mb_money);

            if($iResult == 1){
				$result->status = "success";
				$result->code = 0;
			} else{
				$result->status = "fail";				
                $result->code = $iSubResult;
            } 

        }

		echo json_encode($result);	

    }


    private function  apiGameBet($arrBetData, &$objUser, $objConfig, $arrRoundData, $modelBet, $iMoneyType){

        $iBetId = 0;
        $iResult = isEnableApiBet($arrBetData, $objUser, $objConfig, $arrRoundData);
        if($iResult == 1){
            $arrEmpRatio = $this->modelMember->getEmployeeRatio($objUser, $arrBetData['amount'], $arrBetData['game'], $arrBetData['mode']);
            //베팅에 성공하면 거래내역에 반영,유저머니 변경
            if($this->modelMember->updateAssets($objUser, 0-$arrBetData['amount'])){
                $iBetId = $modelBet->register($arrBetData, $objUser);
                $this->modelMoneyhist->registerBet($objUser, $arrBetData, $iMoneyType);
            }
        }

        if($iResult == 1 && $iBetId > 0){			
            // $this->modelMember->updateRewards($arrEmpRatio);
            $this->modelReward->register($arrBetData['game'], $iBetId, $arrEmpRatio);
        }
        return $iResult;
    }

    public function apispd(){
        /****
         * 데이터 가져오기
         */
        /*
        // 콜백 토큰
        $CALLBACK_TOKEN = 'f9469bed-d017-44e0-9e61-5906be034c1e';

        $headers = apache_request_headers();
        $token_data = trim($headers['Callback-Token']);

        // 콜백 토큰 체크
        if ($CALLBACK_TOKEN != $token_data) {
            $result = [
                'result' => 100, // 콜백 토큰이 다르다면 100번으로 리턴
                'status' => 'ERROR',
            ];

            echo json_encode($result);
            exit;
        }

        $requestData = file_get_contents('php://input');
        $oData = json_decode($requestData);


        $command = $oData->command;
        $request_timestamp = $oData->request_timestamp; // UTC 기준

        $aCheckItem = explode(',', $oData->check);

        ///////////////////////
        // 해당 항목 오류가 없는지 검사
        ///////////////////////
        // 아래 점검 사항에서 구한
        // 사용자 정보, 베팅 정보 등은 하단의 데이터 처리에서 사용하면 됨
        ////////////////////////////////////////////////////////////

        $objUser = null;
        $betInfo = '';
        foreach ($aCheckItem as $check) {
            switch ($check) {
                case 21:    // 사용자 확인
                    $mb_uid = $oData->data->user_id;

                    $objUser = $this->modelMember->getByUid($mb_uid);

                    if (is_null($objUser)) {
                        $aResult = [
                            'result' => $check,
                            'status' => 'ERROR',
                        ];

                        echo json_encode($aResult);
                        exit;
                    }
                    break;

                case 22:    // 활동 회원인지 확인
                    if (!$this->modelMember->isPermitMember($objUser)) {    // 활동 회원이 아닐 때
                        $aResult = [
                            'result' => $check,
                            'status' => 'ERROR',
                        ];

                        echo json_encode($aResult);
                        exit;
                    }
                    break;

                case 31: // 보유 머니 확인
                    $amount = intval($oData->data->amount); // 베팅, 적중 금액 (DECIMAL 18,2)  원화이므로 int로 변환해서 처리하면 안전.

                    if (intval($objUser->mb_money) < $amount) {   // 베팅 금액보다 보유 금액이 적을 때
                        $aResult = [
                            'result' => $check,
                            'status' => 'ERROR',
                            'data' => [
                                'balance' => $objUser->mb_money,
                            ],
                        ];

                        echo json_encode($aResult);
                        exit;
                    }
                    break;

                case 41:    // 이미 처리한 transaction인지 확인(베팅,적중)
                    $transaction_id = $oData->data->transaction_id; // 유일한 string 값인지 확인

                    $sql = "SELECT * FROM bet_casino WHERE transaction_id='{$transaction_id}'";
                    $result = $dbConn->query($sql);

                    // transaction id는 모두 저장해둬야 하고
                    // 작업 처리여부 점검
                    if ($result->num_rows > 0) {
                        $aResult = [
                            'result' => 0,
                            'status' => 'OK',
                            'data' => [
                                'balance' => $objUser->mb_money,
                            ],
                        ];

                        echo json_encode($aResult);
                        exit;
                    }
                    break;

                case 42:   // transaction 아이디가 존재하는지 확인(베팅 취소시)
                    $transaction_id = $oData->data->transaction_id;  // 유일한 string 값

                    // transaction id는 모두 저장해둬야 하고
                    // 작업 처리여부 점검
                    $sql = "SELECT * FROM bet_casino WHERE transaction_id='{$transaction_id}'";
                    $result = $dbConn->query($sql);

                    // transaction id는 모두 저장해둬야 하고
                    // 작업 처리여부 점검
                    if ($result->num_rows > 0) {
                        $betInfo = $result->fetch_assoc();
                    } else {
                        $aResult = [
                            'result' => $check,
                            'status' => 'ERROR',
                            'data' => [
                                'balance' => $objUser->mb_money,
                            ],
                        ];

                        echo json_encode($aResult);
                        exit;
                    }
                    break;
            }
        }

        //////////////////////
        // 정상 완료 처리
        //////////////////////
        $aResult = [];
        switch ($command) {
            case "authenticate":
                $aResult = [
                    'result' => 0,
                    'status' => 'OK',
                    'data' => [
                        'account' => $objUser->mb_uid,  // 유일한 사용자 ID (string : 4 ~ 15까지 가능)
                        'nickname' => $objUser->mb_nickname,   // 사용자 닉네임 (string : 2 ~ 15까지 가능)
                        'balance' => $objUser->mb_money,
                    ],
                ];
                break;

            case "balance":
                $aResult = [
                    'result' => 0,
                    'status' => 'OK',
                    'data' => [
                        'balance' => $objUser->mb_money,
                    ],
                ];
                break;

            case "bet": // 주문 요청 처리
                $transaction_id = $oData->data->transaction_id;  // 유일한 string 값
                $transaction_timestamp = $oData->timestamp;   // transaction 발생 시간 (UTC)
                $amount = intval($oData->data->amount); // 베팅, 적중 금액 (DECIMAL 18,2)  원화이므로 int로 변환해서 처리하면 안전.
                $game_id = $oData->data->game_id;   // 게임 ROUND ID (여러 bet, win에 대한 관련 내역을 식별하는 ID)
                $game_type = $oData->data->game_type;   // 게임 종류 (바카라, 포커 등...)
                $round_id = $oData->data->round_id; // 게임 ROUND 참조 ID


                // 베팅 내역 기록하고
                // 사용자 베팅 금액 만큼 보유금액 차감

                $user_id = $userInfo['user_id'];
                $request_datetime = time();
                $sql = "INSERT INTO bet_casino VALUES ('{$transaction_id}', '{$user_id}', '{$game_id}', '{$round_id}', 'BET', '{$amount}', '{$request_datetime}')";
                $result = $dbConn->query($sql);

                if (!$result) {
                    $aResult = [
                        'result' => 99,
                        'status' => 'ERROR',
                        'data' => [
                            'balance' => $userInfo['money'],
                        ],
                    ];

                    echo json_encode($aResult);
                    exit;
                }


                // transaction 정보 저장
                ////////////////////////

                $user_money = $userInfo['money'] - $amount;
                $sql = "UPDATE user_casino SET money='{$user_money}' WHERE user_id='{$user_id}'";
                $result = $dbConn->query($sql);

                // 관련 작업 모두 성공
                $aResult = [
                    'result' => 0,
                    'status' => 'OK',
                    'data' => [
                        'balance' => $user_money,
                    ],
                ];
                break;

            case "win":    // 주문 항목 적중, 미적중 처리
                $transaction_id = $oData->data->transaction_id;  // 유일한 string 값
                $amount = intval($oData->data->amount); // 베팅, 적중 금액 (DECIMAL 18,2)  원화이므로 int로 변환해서 처리하면 안전.
                $game_id = $oData->data->game_id;   // 게임 ROUND ID (여러 bet, win에 대한 관련 내역을 식별하는 ID)
                $round_id = $oData->data->round_id; // 게임 ROUND 참조 ID

                $user_id = $userInfo['user_id'];
                $request_datetime = time();
                $sql = "INSERT INTO bet_casino VALUES ('{$transaction_id}', '{$user_id}', '{$game_id}', '{$round_id}', 'WIN', '{$amount}', '{$request_datetime}')";
                $result = $dbConn->query($sql);

                $user_money = $userInfo['money'] + $amount;
                if ($result) {
                    if ($amount > 0) {
                        $sql = "UPDATE user_casino SET money='{$user_money}' WHERE user_id='{$user_id}'";
                        $result = $dbConn->query($sql);
                    }
                } else {
                    $aResult = [
                        'result' => 99,
                        'status' => 'ERROR',
                        'data' => [
                            'balance' => $userInfo['money'],
                        ],
                    ];

                    echo json_encode($aResult);
                    exit;
                }


                // 관련 작업 모두 성공
                $aResult = [
                    'result' => 0,
                    'status' => 'OK',
                    'data' => [
                        'balance' => $user_money,
                    ],
                ];
                break;

            case "cancel":
                $money = 0;
                $user_id = $userInfo['user_id'];
                $user_money = $userInfo['money'];

                if ($betInfo['sort'] != "CANCEL") {    // 취소 처리가 필요한 상황
                    // 취소 작업 진행
                    // transaction id가 BET 관련된 건 베팅 금액 관련 사항만 취소.  당첨 금액 관련 사항은 취소하면 안됨.  당첨 금액 관련 취소는 별도로 요청이 옴.
                    // transaction id가 WIN 관련된 건 당첨 금액 관련 사항만 취소.  베팅 금액은 취소하면 안됨.

                    if ($betInfo['sort'] == "BET") {   // bet 처리한 transaction id와 일치할 때
                        // 베팅 취소 처리 진행
                        $money = $betInfo['money'];

                        $sql = "UPDATE bet_casino SET sort='CANCEL' WHERE transaction_id='{$transaction_id}'";
                        $result = $dbConn->query($sql);

                        if ($result) {
                            $user_money = $userInfo['money'] + $money;
                            $sql = "UPDATE user_casino SET money='{$user_money}' WHERE user_id='{$user_id}'";
                            $result = $dbConn->query($sql);
                        } else {
                            $aResult = [
                                'result' => 99,
                                'status' => 'ERROR',
                                'data' => [
                                    'balance' => $userInfo['money'],
                                ],
                            ];

                            echo json_encode($aResult);
                            exit;
                        }
                    } else if ($betInfo['sort'] == "WIN") {
                        // 결과 취소 처리 진행
                        $money = -1 * $betInfo['money'];

                        $sql = "UPDATE bet_casino SET sort='CANCEL' WHERE transaction_id='{$transaction_id}'";
                        $result = $dbConn->query($sql);

                        if ($result) {
                            $user_money = $userInfo['money'] + $money;
                            $sql = "UPDATE user_casino SET money='{$user_money}' WHERE user_id='{$user_id}'";
                            $result = $dbConn->query($sql);
                        } else {
                            $aResult = [
                                'result' => 99,
                                'status' => 'ERROR',
                                'data' => [
                                    'balance' => $userInfo['money'],
                                ],
                            ];

                            echo json_encode($aResult);
                            exit;
                        }
                    }
                }

                $aResult = [
                    'result' => 0,
                    'status' => 'OK',
                    'data' => [
                        'balance' => $user_money,
                    ],
                ];
                break;

            case "status":
                $transaction_id = $oData->data->transaction_id;  // 유일한 string 값

                if ($betInfo['sort'] == "CANCEL") {    // 취소 처리한 상황
                    $aResult = [
                        'result' => 0,
                        'status' => 'OK',
                        'data' => [
                            'account' => $userInfo['user_id'],
                            'transaction_id' => $transaction_id,
                            'transaction_status' => 'CANCELED',
                        ],
                    ];
                } else {
                    $aResult = [
                        'result' => 0,
                        'status' => 'OK',
                        'data' => [
                            'account' => $userInfo['user_id'],
                            'transaction_id' => $transaction_id,
                            'transaction_status' => 'OK',
                        ],
                    ];
                }
                break;
        }

        echo json_encode($aResult);
        */
    }
}