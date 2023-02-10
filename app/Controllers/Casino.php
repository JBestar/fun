<?php namespace App\Controllers;

use App\Models\Transfer_Model;

class Casino extends BaseController
{
    public function index()
    {
        $this->response->redirect('/');	
    }

    public function evl()
	{
						
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else {
            $logHead = "<CAS_EVOL>";
			$gameId = GAME_CASINO_EVOL;
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //에볼 설정
			$headInfo = $this->getSiteConf();
			$objCas = $this->modelCasprd->getById($gameId, 0);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = null;
			if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1){
				$sess = $this->modelSess->getByUid($objMember->mb_uid, SESS_TYPE_APP);
			}

			writeLog($logHead.$objMember->mb_uid." Call Play");
            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//준비중
            else if($headInfo['evol_deny'])
                $iCreated = 3;
			else if(is_null($objCas))
				$iCreated = 7;									//게임정보오류
			else if($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE)
				$iCreated = 7;										
			else if(!$this->modelMember->isPermitMember($objMember, GAME_CASINO_EVOL))   //차단
				$iCreated = 3;									
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sess))
				$iCreated = 9;	
			else if($objMember->mb_live_id == 0){
				//플레이어 창조
				$createId = createGameId(substr($_ENV['app.name'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiCas->createUser($createId, $objMember->mb_nickname);
                
                if($arrResult['status'] == 1){
                    $objMember->mb_live_id = $arrResult['user_id'];
                    $objMember->mb_live_uid = $createId;
                    $this->modelMember->updateLiveInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
                } else {
                    if(array_key_exists('error', $arrResult) && $arrResult['error'] == DOUBLE_USER){
                        usleep(500000);
						$arrResult = $this->libApiCas->getUserInfo($createId);
						writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
                        if($arrResult['status'] == 1){
							$objMember->mb_live_id = $arrResult['user_id'];
							$objMember->mb_live_uid = $arrResult['site_id'];
							$objMember->mb_live_money = $arrResult['balance'];
							$this->modelMember->updateLiveInfo($objMember);
							$iCreated = 1;
						} else $iCreated = 5;								//중복
					} else {
						$iCreated = 2;								//회원창조실패
						if(array_key_exists('error', $arrResult))
		                    writeLog($logHead.$objMember->mb_uid."-CreateUser Error=".$arrResult['error']); 
					}
                }
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('준비중입니다'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('중복된 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".(DELAY_GAME-$diffDt)."초후 다시 시도해주세요.'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
                if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, GAME_CASINO_EVOL);
				else $iResult = 1;

				if($iResult == 1){
                    $arrResult = $this->libApiCas->auth($objMember->mb_live_uid, $objMember->mb_nickname, $objMember->mb_live_money);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
                        writeLog($logHead.$arrResult['launch_url']);
						$this->modelMember->updateBetTm($objMember);
						// echo view('slot/game', array("game" => GAME_CASINO_EVOL, "launch_url" => $arrResult['launch_url']));	
                        $this->response->redirect($arrResult['launch_url']);
                    } else {
                        if(array_key_exists('error', $arrResult) && $arrResult['error'] == INVALID_USER){
                            print "<script language=javascript> alert('존재하지 않는 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
                        }
                        else {
                            print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
                        } 
                    }
                } else { //머니이동 실패경우
					print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
				}
			}
			
        }

    }

    public function cas()
	{
						
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else {
			$gameId = GAME_CASINO_KGON;
            $logHead = "<CAS_KGON>";

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find(GAME_CASINO_KGON);  //카지노 설정
			$headInfo = $this->getSiteConf();
			$prdId = trim($this->request->getVar('prd'));
			$objCas = $this->modelCasprd->getById($gameId, $prdId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
            $sess = null;
			if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1){
				$sess = $this->modelSess->getByUid($objMember->mb_uid, SESS_TYPE_APP);
			}

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
            else if(is_null($objCas))
				$iCreated = 6;									//게임정보오류
            else if($objCas->maintain == STATE_ACTIVE || $objCas->hidden == STATE_ACTIVE)
				$iCreated = 7;
			else if($objConfig->game_bet_permit != PERMIT_OK)
				$iCreated = 4;									//준비중
            else if($headInfo['cas_deny'])
                $iCreated = 3;									//차단
			else if(!$this->modelMember->isPermitMember($objMember, GAME_CASINO_KGON))
				$iCreated = 3;									//차단
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sess))
				$iCreated = 9;	
			else if($objMember->mb_kgon_id == 0){
				//플레이어 창조
                $createId = createGameId(substr($_ENV['app.name'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiKgon->createUser($createId, $objMember->mb_nickname, $objMember->mb_uid);
                
                if($arrResult['status'] == 1){
                    $objMember->mb_kgon_id = $arrResult['id'];
                    $objMember->mb_kgon_uid = $createId;
                    $this->modelMember->updateKgonInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
                } else {
                    if(array_key_exists('code', $arrResult) && $arrResult['code'] == -500)
                        $iCreated = 5;								//중복
                    else $iCreated = 2;								//회원창조실패
                    
                    if(array_key_exists('code', $arrResult))
                        writeLog($logHead.$objMember->mb_uid."-CreateUser Error code=".$arrResult['code']." Msg".$arrResult['msg']); 
                }
			} else{
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정창조중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('준비중입니다'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('중복된 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('게임을 정확히 선택해주세요.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".(DELAY_GAME-$diffDt)."초후 다시 시도해주세요.'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				if(array_key_exists('app.transEg', $_ENV) && $_ENV['app.transEg'] == 1)
					$iResult = $this->alltoGame($objMember, GAME_CASINO_KGON);
				else $iResult = 1;
                if($iResult != 1){
                    print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
                } else {
                    $arrResult = $this->libApiKgon->auth($objMember->mb_kgon_uid, $objMember->mb_nickname, $objMember->mb_uid, $objCas->key, $objCas->lobby);
                    if($arrResult['status'] == 1){
                        writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
                        writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						// echo view('slot/game', array("game" => GAME_CASINO_KGON, "launch_url" => $arrResult['url']));	
                        $this->response->redirect($arrResult['url']);
                    } else {
                        if(array_key_exists('code', $arrResult)){
							$log = $logHead.$objMember->mb_uid."-Auth Error code=".$arrResult['code'];
							if(array_key_exists('msg', $arrResult))
								$log.=" msg=".$arrResult['msg'];
							writeLog($log); 
						}
                        print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
                    }
                }
				
			}
			
        }

    }

}