<?php

namespace App\Controllers;

use App\Models\SlotPrd_Model;
use App\Models\SlotGame_Model;

class Home extends BaseController
{
    public function index()
    {
        $headInfo = $this->getSiteConf();

        $objMember = null;
        if(is_login()){
            $user_id = $this->session->user_id;
            $objMember = $this->modelMember->getByUid($user_id);
        }
		$navInfo = getNavInfo($objMember);
        $navInfo += $this->casinoPrd($headInfo);
		$navInfo += $this->slotPrd($headInfo);
        $navInfo['notice_main'] = $this->modelConfsite->getMainNotice();
        $navInfo['notice_bank'] = $this->modelConfsite->find(CONF_NOTICE_BANK);
        $navInfo['notice_urgent'] = $this->modelConfsite->find(CONF_NOTICE_URGENT);

        echo view('home/main', $headInfo+$navInfo);

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
