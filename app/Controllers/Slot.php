<?php namespace App\Controllers;

use App\Models\SlotPrd_Model;
use App\Models\SlotGame_Model;
use App\Models\Transfer_Model;

class Slot extends BaseController
{

    public function index()
    {
        $this->response->redirect('/');	
    }

	public function xslotlist()
	{
		$prdCode = trim($this->request->getVar('prd'));
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else if($_ENV['app.type'] == APPTYPE_2 || $_ENV['app.type'] == APPTYPE_5 || $_ENV['app.type'] == APPTYPE_7 || $_ENV['app.type'] == APPTYPE_9 ){
			$this->response->redirect('/fslotlist?prd='.$prdCode);	
		}  else {
			$gameId = GAME_SLOT_THEPLUS;

			$modelSlotgame = new SlotGame_Model();
            $prdCode = trim($this->request->getVar('prd'));
            $objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //슬롯1
			$headInfo = $this->getSiteConf();

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($objPrd))
				$iCreated = 0;
			// else if($objConfig->game_bet_permit != PERMIT_OK){
			// 	$iCreated = 4;									//준비중
			// }
			else if($headInfo['slot_deny'])
                $iCreated = 3;									//차단
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//차단
			else {
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
			} else if($iCreated == 1){

				$games = $modelSlotgame->gets($gameId, $objPrd->code);

				writeLog("<SLOT PRD> Code:".$objPrd->code." Count:".count($games));

                echo view('home/slotlist', array("prd" => $objPrd->code, "games" => $games));

			}
        }

    }

	public function xslot(){
		
		$prdCode = trim($this->request->getVar('prd'));
		$slotId = trim($this->request->getVar('game'));

		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";
        } else if($_ENV['app.type'] == APPTYPE_2){
			$this->response->redirect('/slot/xslotf?prd='.$prdCode.'&game='.$slotId);	
		} else if($_ENV['app.type'] == APPTYPE_5){
			$this->response->redirect('/slot/xslotg?prd='.$prdCode.'&game='.$slotId);	
		} else if($_ENV['app.type'] == APPTYPE_7){
			$this->response->redirect('/slot/xslotk?prd='.$prdCode.'&game='.$slotId);	
		} else if($_ENV['app.type'] == APPTYPE_9){
			$this->response->redirect('/slot/xsloth?prd='.$prdCode.'&game='.$slotId);	
		} else {

			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_THEPLUS;
			$logHead = "<XSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id, true);
			$objConfig = $this->modelConfgame->find($gameId);  //슬롯1

			
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$headInfo = $this->getSiteConf();
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, SESS_TYPE_APP);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($headInfo['slot_deny'])
                $iCreated = 3;									//차단
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//차단
			else if(is_null($objSlot) || is_null($objPrd))
				$iCreated = 6;
			else if($objSlot->maintain == STATE_ACTIVE)
				$iCreated = 7;
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;	
			else if(!is_null($sess))
				$iCreated = 9;	
			else {
				$objFslot  = null;
					
				if($_ENV['app.type'] == APPTYPE_1 || $_ENV['app.type'] == APPTYPE_4 || $_ENV['app.type'] == APPTYPE_6 || $_ENV['app.type'] == APPTYPE_8) {

					if($objSlot->act >= STATE_ACTIVE){
						if($_ENV['app.type'] == APPTYPE_1 && $objSlot->ref_prd > 0){
							$objFslot = $modelSlotgame->getById(GAME_SLOT_GSPLAY, $objSlot->ref_prd, $objSlot->ref_uuid);
						}

						if($objFslot == null){
							$fgameId = GAME_SLOT_GSPLAY;
							if($_ENV['app.type'] == APPTYPE_4)
								$fgameId = GAME_SLOT_GOLD;
							else if($_ENV['app.type'] == APPTYPE_6)
								$fgameId = GAME_SLOT_KGON;
							else if($_ENV['app.type'] == APPTYPE_8)
								$fgameId = GAME_SLOT_STAR;

							$refPrds = $this->modelSlotprd->getByRef($fgameId, $objSlot->prd_code); 
							writeLog($logHead.$objMember->mb_uid." PRD=".$objSlot->prd_code." REF=".count($refPrds)." ACT=".$objSlot->act);

							if(count($refPrds) > 1){

								if($objSlot->act == STATE_ACTIVE + 1)
									$objFslot = $modelSlotgame->getByName($fgameId, $refPrds[1]->code, $objSlot->name );
								else {
									$objFslot = $modelSlotgame->getByName($fgameId, $refPrds[0]->code, $objSlot->name );
									if($objFslot == null)									
										$objFslot = $modelSlotgame->getByName($fgameId, $refPrds[1]->code, $objSlot->name );
								}
							}
							else {
								if($_ENV['app.type'] == APPTYPE_4)
									$objFslot = $modelSlotgame->getByNameKo($fgameId, $refPrds[0]->code, $objSlot->name_ko );
								else 
									$objFslot = $modelSlotgame->getByName($fgameId, $refPrds[0]->code, $objSlot->name );
							}
						}
						
					}
				}

				if(!is_null($objFslot)){
					writeLog("<FSLOT>".$objMember->mb_uid." PRD=".$objFslot->prd_code." NAME=".$objFslot->name_ko);
					$iCreated = 100;
				} else if($objConfig->game_bet_permit != PERMIT_OK){			//준비중
						$iCreated = 4;									
				} else if($objMember->mb_slot_uid == ""){
					//플레이어 창조
					$createId = createGameId(substr($_ENV['app.name'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
					$arrResult =  $this->libApiSlot->createUser($createId);
					if($arrResult['status'] == 1){
						$objMember->mb_slot_uid = $createId;
						$this->modelMember->updateSlotInfo($objMember);
						$iCreated = 1;
	
						writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
					} else {
						if(array_key_exists('resultCode', $arrResult) && $arrResult['resultCode'] == SLOTCODE_DOUBLE_USER) {
							usleep(500000);
							$arrResult = $this->libApiSlot->getUserInfo($createId);
							writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
							if($arrResult['status'] == 1){
								$objMember->mb_slot_uid = $createId;
								$objMember->mb_slot_money = $arrResult['balance'];
								$this->modelMember->updateSlotInfo($objMember);
								$iCreated = 1;
	
							} else $iCreated = 5;								//중복
						}
						else $iCreated = 2;								//회원창조실패
						writeLog($logHead.$objMember->mb_uid."-CreateUser resultCode=".$arrResult['resultCode']); 
					}
				} else {
					$iCreated = 1;
				}
			}
			
			if($iCreated == 0){
				print "<script language=javascript> alert('관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('준비중입니다'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('중복된 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".(DELAY_GAME-$diffDt)."초후 다시 시도해주세요.'); self.close(); </script>";
			}  else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 100){
				if($_ENV['app.type'] == APPTYPE_1)
					$this->response->redirect('/xslotf?prd='.$objFslot->prd_code.'&game='.$objFslot->uuid);	
				else if($_ENV['app.type'] == APPTYPE_4)
					$this->response->redirect('/xslotg?prd='.$objFslot->prd_code.'&game='.$objFslot->uuid);	
				else if($_ENV['app.type'] == APPTYPE_6)
					$this->response->redirect('/xslotk?prd='.$objFslot->prd_code.'&game='.$objFslot->uuid);	
				else if($_ENV['app.type'] == APPTYPE_8)
					$this->response->redirect('/xsloth?prd='.$objFslot->prd_code.'&game='.$objFslot->uuid);	
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid."-Slot Game=>".$objSlot->name ); 
				$iResult = $this->alltoGame($objMember, $gameId);

				if($iResult != 1){ //머니이동 실패경우
					print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
				} else {
					$arrResult =  $this->libApiSlot->createSess($objMember->mb_slot_uid);
					if($arrResult['status'] != 1) {
						writeLog($logHead.$objMember->mb_uid."-CreateSess resultCode=".$arrResult['resultCode']); 
						print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
					}
					else{
						writeLog($logHead.$objMember->mb_uid."-CreateSess ID=".$arrResult['session']); 

						$arrResult =  $this->libApiSlot->getLink($arrResult['session'], $objSlot->uuid);

						if($arrResult['status'] == 1){
							writeLog($logHead.$objMember->mb_uid."-Link Sucess !!");
							writeLog($logHead.$arrResult['gameUrl']);
							$this->modelMember->updateBetTm($objMember);
							// echo view('slot/game', array("game" => GAME_CASINO_EVOL, "launch_url" => $arrResult['gameUrl']));	
							$this->response->redirect($arrResult['gameUrl']);
						} else {
							writeLog($logHead.$objMember->mb_uid."-Link resultCode=".$arrResult['resultCode']);
							if(array_key_exists('resultCode', $arrResult) && $arrResult['resultCode'] == SLOTCODE_USER_NONE){
								print "<script language=javascript> alert('존재하지 않는 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
							}
							else {
								print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
							} 
						}
					}
				}  
			}
			
		}
	}


	public function xslotf(){
		$prdCode = trim($this->request->getVar('prd'));
		$slotId = trim($this->request->getVar('game'));
			
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else if($_ENV['app.type'] == APPTYPE_5){
			$this->response->redirect('/slot/xslotg?prd='.$prdCode.'&game='.$slotId);	
		} else if($_ENV['app.type'] == APPTYPE_7){
			$this->response->redirect('/slot/xslotk?prd='.$prdCode.'&game='.$slotId);	
		} else if($_ENV['app.type'] == APPTYPE_9){
			$this->response->redirect('/slot/xsloth?prd='.$prdCode.'&game='.$slotId);	
		} else {
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_GSPLAY;
			$logHead = "<FSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //슬롯2

			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, SESS_TYPE_APP);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//준비중
			}
			else if($_ENV['app.type'] == APPTYPE_2 && !$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//차단
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			else if($_ENV['app.type'] == APPTYPE_2 && $objSlot->maintain == STATE_ACTIVE){
				$iCreated = 7;
			} 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_fslot_id == 0){
				//플레이어 창조
				$createId = createGameId(substr($_ENV['app.name'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiFslot->createUser($createId, $objMember->mb_nickname);
				if($arrResult['status'] == 1){
					$objMember->mb_fslot_id = $arrResult['gs_user_id'];
					$objMember->mb_fslot_uid = $createId;
					$this->modelMember->updateFslotInfo($objMember);
					$iCreated = 1;

					writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
					if(array_key_exists('error', $arrResult) && $arrResult['error'] == DOUBLE_USER){
						usleep(500000);
						$arrResult = $this->libApiFslot->getUserInfo($createId);
						writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
                        if($arrResult['status'] == 1){
							$objMember->mb_fslot_id = $arrResult['gs_user_id'];
							$objMember->mb_fslot_uid = $arrResult['user_id'];
							$objMember->mb_fslot_money = $arrResult['balance'];
							$this->modelMember->updateFslotInfo($objMember);
							$iCreated = 1;

						} else 
							$iCreated = 5;								//중복
					} else $iCreated = 2;								//회원창조실패
					if(array_key_exists('error', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser Error=".$arrResult['error']); 
				}
			} else {
				$iCreated = 1;
				if($_ENV['app.type'] == APPTYPE_2 && $objSlot->prd_code == 200 && $objSlot->act ==  1){
					$prdCode = 215;
					$objNSlot = $modelSlotgame->getByCode($gameId, $prdCode, $objSlot->game_code);
					if(!is_null($objNSlot)) {
						$objSlot = $objNSlot;
					}
				}
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('준비중입니다'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('중복된 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".(DELAY_GAME-$diffDt)."초후 다시 시도해주세요.'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiFslot->auth($objMember->mb_fslot_uid, $objMember->mb_nickname, $objSlot);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['launch_url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['launch_url']);
						// echo view('slot/game', array("game" => $gameId, "launch_url" => $arrResult['launch_url']));	
					} else {
						if(array_key_exists('error', $arrResult) && $arrResult['error'] == INVALID_USER){
							print "<script language=javascript> alert('존재하지 않는 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
						}
						else {
							if(array_key_exists('error', $arrResult)) {
								writeLog($logHead.$objMember->mb_uid."launchError=".$arrResult['error']);
							}
							print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
						} 
					}
				} else { //머니이동 실패경우
					print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
				}
				 
			}

		}
	}

	
	public function xslotg(){
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else {
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_GOLD;
			$logHead = "<GSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //슬롯2

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, SESS_TYPE_APP);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//준비중
			}
			else if($_ENV['app.type'] == APPTYPE_5 && !$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//차단
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			else if($_ENV['app.type'] == APPTYPE_5 && $objSlot->maintain == STATE_ACTIVE){
				$iCreated = 7;
			} 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_gslot_uid == ""){
				//플레이어 창조
				$createId = createGameId(substr($_ENV['app.name'], 0, 2)."_".$objMember->mb_fid);//."_".$objMember->mb_uid
				$arrResult = $this->libApiGslot->createUser($createId);
				if($arrResult['status'] == 1){
					$objMember->mb_gslot_uid = $createId;
					$objMember->mb_gslot_money = $arrResult['user_slot_balance'];
					$this->modelMember->updateGslotInfo($objMember);
					$iCreated = 1;

					writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
					if(array_key_exists('msg', $arrResult) && $arrResult['msg'] == "DUPLICATE_USER"){
						usleep(500000);
						$arrResult = $this->libApiGslot->getUserInfo($createId);
						writeLog($logHead.$objMember->mb_uid."-Double UserInfo Status=".$arrResult['status']);
                        if($arrResult['status'] == 1){
							$objMember->mb_gslot_uid = $arrResult['user']['user_code'];
							$objMember->mb_gslot_money = $arrResult['balance'];
							$this->modelMember->updateGslotInfo($objMember);
							$iCreated = 1;

						} else 
							$iCreated = 5;								//중복
					} else $iCreated = 2;								//회원창조실패
					if(array_key_exists('msg', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser msg=".$arrResult['msg']); 
				}
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('준비중입니다'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('중복된 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".(DELAY_GAME-$diffDt)."초후 다시 시도해주세요.'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiGslot->auth($objMember->mb_gslot_uid, $objSlot->provider, $objSlot->game_code);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['launch_url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['launch_url']);
						// echo view('slot/game', array("game" => $gameId, "launch_url" => $arrResult['launch_url']));	
					} else {
						if(array_key_exists('msg', $arrResult) && $arrResult['msg'] == "INVALID_USER"){
							print "<script language=javascript> alert('존재하지 않는 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
						}
						else {
							if(array_key_exists('msg', $arrResult)) {
								writeLog($logHead.$objMember->mb_uid."launch msg=".$arrResult['msg']);
							}
							print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
						} 
					}
				} else { //머니이동 실패경우
					print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
				}
				 
			}

		}
	}

	public function xslotk(){
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else {
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_KGON;
			$logHead = "<KSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //슬롯2

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, SESS_TYPE_APP);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//준비중
			}
			else if($_ENV['app.type'] == APPTYPE_7 && !$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//차단
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			else if($_ENV['app.type'] == APPTYPE_7 && $objSlot->maintain == STATE_ACTIVE){
				$iCreated = 7;
			} 
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
					if(array_key_exists('code', $arrResult) && $arrResult['code'] == -500){
						$iCreated = 5;								//중복 
					} else {
						$iCreated = 2;								//회원창조실패
					}

					if(array_key_exists('code', $arrResult))
					writeLog($logHead.$objMember->mb_uid."-CreateUser Error code=".$arrResult['code']." Msg=".$arrResult['msg']); 
				}
			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('준비중입니다'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('중복된 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".(DELAY_GAME-$diffDt)."초후 다시 시도해주세요.'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiKgon->auth($objMember->mb_kgon_uid, $objMember->mb_nickname, $objMember->mb_uid, $objSlot->provider, $objSlot->game_code);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
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
				} else { //머니이동 실패경우
					print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
				}
				 
			}

		}
	}

	public function xsloth(){
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else {
			$modelSlotgame = new SlotGame_Model();

			$gameId = GAME_SLOT_STAR;
			$logHead = "<HSLOT>";
			
			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //슬롯2

			$prdCode = trim($this->request->getVar('prd'));
			$slotId = trim($this->request->getVar('game'));
			$objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);
			$objSlot = $modelSlotgame->getById($gameId, $prdCode, $slotId);
			$diffDt = diffDt(date('Y-m-d H:i:s'), $objMember->mb_time_call) ;
			$sess = $this->modelSess->getByUid($objMember->mb_uid, SESS_TYPE_APP);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig))
				$iCreated = 0;
			else if($objConfig->game_bet_permit != PERMIT_OK){
				$iCreated = 4;									//준비중
			}
			else if($_ENV['app.type'] == APPTYPE_9 && !$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//차단
			else if(is_null($objSlot) || is_null($objPrd)){
				$iCreated = 6;
			} 
			else if($_ENV['app.type'] == APPTYPE_9 && $objSlot->maintain == STATE_ACTIVE){
				$iCreated = 7;
			} 
			else if($diffDt < DELAY_GAME)
				$iCreated = 8;
			else if(!is_null($sess))
				$iCreated = 9;
			else if($objMember->mb_fslot_uid == ""){
				writeLog($logHead.$objMember->mb_uid."-1");
				//플레이어 창조
				$arrResult = $this->libApiHslot->createUser($objMember->mb_uid, $objMember->mb_nickname);

				if($arrResult['status'] == 1){

					$objMember->mb_fslot_uid = $arrResult['data']['token'];
                    $objMember->mb_fslot_money = 0;
                    $this->modelMember->updateFslotInfo($objMember);
                    $iCreated = 1;

                    writeLog($logHead.$objMember->mb_uid."-CreateUser Sucess !!");
				} else {
					writeLog($logHead.$objMember->mb_uid."-2");

					$iCreated = 2;								//회원창조실패
					if(array_key_exists('description', $arrResult))
						writeLog($logHead.$objMember->mb_uid."-CreateUser Error description=".$arrResult['description']); 
				}
				writeLog($logHead.$objMember->mb_uid."-3");

			} else {
				$iCreated = 1;
			}

			if($iCreated == 0){
				print "<script language=javascript> alert('관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 2){
				print "<script language=javascript> alert('계정생성중 오류가 발생하였습니다.'); self.close(); </script>";
			} else if($iCreated == 3){
				print "<script language=javascript> alert('게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 4){
				print "<script language=javascript> alert('준비중입니다'); self.close(); </script>";
			} else if($iCreated == 5){
				print "<script language=javascript> alert('중복된 사용자입니다. 관리자에게 문의해주세요.'); self.close(); </script>";
			} else if($iCreated == 6){
				print "<script language=javascript> alert('존재하지 않는 게임입니다.'); self.close(); </script>";
			} else if($iCreated == 7){
				print "<script language=javascript> alert('점검중입니다.'); self.close(); </script>";
			} else if($iCreated == 8){
				print "<script language=javascript> alert('".(DELAY_GAME-$diffDt)."초후 다시 시도해주세요.'); self.close(); </script>";
			} else if($iCreated == 9){
				print "<script language=javascript> alert('앱이 실행중이므로 게임실행이 중지되었습니다.'); self.close(); </script>";
			} else if($iCreated == 1){
				writeLog($logHead.$objMember->mb_uid." Game=>".$objSlot->prd_code.":".$objSlot->name_ko ); 

				$iResult = $this->alltoGame($objMember, $gameId);
				if($iResult == 1){
					$arrResult = $this->libApiHslot->auth($objMember->mb_fslot_uid, $objSlot);
					if($arrResult['status'] == 1){
						writeLog($logHead.$objMember->mb_uid."-Login Sucess !!");
						writeLog($logHead.$arrResult['url']);
						$this->modelMember->updateBetTm($objMember);
						$this->response->redirect($arrResult['url']);
					} else {
						if(array_key_exists('description', $arrResult)){
							$log = $logHead.$objMember->mb_uid."-Auth Error description=".$arrResult['description'];
							writeLog($log); 
						} 
                        print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
					}
				} else { //머니이동 실패경우
					print "<script language=javascript> alert('게임서버가 응답하지 않습니다. 잠시후 다시 시도해주세요.'); self.close(); </script>";
				}
				 
			}
		}
	}

	public function fslotlist()
	{
						
		if(!is_login())
		{
			print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";

        } else if($_ENV['app.type'] == APPTYPE_1 || $_ENV['app.type'] == APPTYPE_3 || $_ENV['app.type'] == APPTYPE_4){
			$this->response->redirect('/xslotlist');	
		}  else {
			if($_ENV['app.type'] == APPTYPE_2)
				$gameId = GAME_SLOT_GSPLAY;
			else
				$gameId = GAME_SLOT_GOLD;

			$modelSlotgame = new SlotGame_Model();
            $prdCode = trim($this->request->getVar('prd'));
            $objPrd = $this->modelSlotprd->getByCode($gameId, $prdCode);

			$user_id = $this->session->user_id;
			$objMember = $this->modelMember->getByUid($user_id);
			$objConfig = $this->modelConfgame->find($gameId);  //슬롯1
			$headInfo = $this->getSiteConf();
			writeLog("<SLOT PRD> Code:".$objPrd->code." Game:".$gameId);

            $iCreated = 0;
			if(is_null($objMember) || is_null($objConfig) || is_null($objPrd))
				$iCreated = 0;
			// else if($objConfig->game_bet_permit != PERMIT_OK){
			// 	$iCreated = 4;									//준비중
			// }
			else if($headInfo['slot_deny'])
                $iCreated = 3;									//차단
			else if(!$this->modelMember->isPermitMember($objMember, $gameId))
				$iCreated = 3;									//차단
			else
				$iCreated = 1;

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
			} else if($iCreated == 1){

				$games = $modelSlotgame->gets($gameId, $objPrd->code);

				writeLog("<SLOT PRD> Code:".$objPrd->code." Count:".count($games));

                echo view('home/slotlist', array("prd" => $objPrd->code, "games" => $games));

			}
        }

    }
}