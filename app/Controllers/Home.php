<?php

namespace App\Controllers;

use App\Models\SlotPrd_Model;
use App\Models\SlotGame_Model;

class Home extends BaseController
{
    public function index()
    {
        $headInfo = $this->getSiteConf();

        if(!is_login() && array_key_exists('app.login', $_ENV) && $_ENV['app.login'] == 1){
            echo view('home/login', $headInfo);
        } else {
            $objMember = null;
            if(is_login()){
                $user_id = $this->session->user_id;
                $objMember = $this->modelMember->getByUid($user_id);
            }
            $navInfo = getNavInfo($objMember);
            $navInfo += $this->casinoPrd($headInfo);
            $navInfo += $this->slotPrd($headInfo);
    
            $boards = array();
            $notice_main = '';
            $arrConf = $this->modelConfsite->getNoticeConf();  
            foreach($arrConf as $objConf){
                switch($objConf->conf_id){
                    case CONF_NOTICE_MAIN: $notice_main = $objConf->conf_content;
                        break;
                    case CONF_NOTICE_BANK: 
                        if($objConf->conf_active == STATE_ACTIVE){
                            $board = new \StdClass;
                            $board->notice_title = '충,환전 공지사항';
                            $board->notice_content = $objConf->conf_content;
                            $board->notice_color = $objConf->conf_idx;
                            $boards[] = $board;
                        }
                        break;
                    case CONF_NOTICE_URGENT: 
                        if($objConf->conf_active == STATE_ACTIVE){
                            $board = new \StdClass;
                            $board->notice_title = '긴 급 공 지 사 항';
                            $board->notice_content = $objConf->conf_content;
                            $board->notice_color = $objConf->conf_idx;
                            $boards[] = $board;
                        }
                        break;
                    default:break;
                }
            }
    
            $reqData['page'] = 1;
            $reqData['count'] = 4;
            $notices = $this->modelNotice->searchBodList($reqData);
            foreach($notices as $notice){
                $notice->notice_color = '#333';
                $boards[] = $notice;
            }

            $navInfo['notice_main'] = $notice_main;
            $navInfo['boards'] = $boards;
    
            $navInfo['part_en'] = true;
            if(array_key_exists('app.hold', $_ENV) && $_ENV['app.hold'] == 1 &&
                !is_null($objMember) && floatval($objMember->mb_game_hl_ratio) == 0) {
                $navInfo['part_en'] = false;
            }

            echo view('home/main', $headInfo+$navInfo);
    
        }


    }

	public function getaddr(){
		$ip = $this->request->getIPAddress();
		echo "IP ADDRESS is <".$ip.">.";
	}

	public function logout(){

		$sess_id = $this->session->session_id;
		writeLog("[home] logout (".$sess_id.")");
		$this->sess_destroy();
		$this->response->redirect('/');
	}

    public function mypage()
    {
        if(!is_login()){
            print "<script> alert('세션이 만료되었습니다. 다시 로그인하세요.'); self.close(); </script>";
        } else{
            $tab = $this->request->getVar('tab');
            $user_id = $this->session->user_id;
            $objMember = $this->modelMember->getByUid($user_id);
            $navInfo = getNavInfo($objMember);

            if($tab != "my_qna" && $tab != "my_memo" && $tab != "notice"){
                $tab = "my_info";
            }
            $navInfo['tab'] = $tab;

            $tmNow = time();
            $navInfo['start_at'] = date('Y-m-d', strtotime("-1 month", $tmNow));
            $navInfo['end_at'] = date('Y-m-d', $tmNow);

            echo view('home/mypage', $navInfo);
        }

    }

	public function pt_login(){
		
		if(array_key_exists('app.furl', $_ENV) && $_ENV['app.furl'] != ""){
			$this->response->redirect($_ENV['app.furl']);
		} else {
			$port = intval($_SERVER['SERVER_PORT']);
			if($port > 0)
				$port += 1;
			else $port = '81';
			$this->response->redirect('http://'.$_SERVER['SERVER_NAME'].':'.$port);
		}
		
	}
}
