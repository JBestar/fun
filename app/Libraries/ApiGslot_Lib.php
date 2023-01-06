<?php 
namespace App\Libraries;

use App\Models\ConfSite_Model;

class ApiGslot_Lib  {
    
    private $mHost = "";    //"https://api.goldslot-link.com/api";
    private $mAgCode = "";  //'ace1';
    private $mAgToken = ""; //'d0fb0291d09495166092b81157c38d82'; 

    function __construct()
    {
        $modelConfsite = new ConfSite_Model();
      
        $objConf = $modelConfsite->find(CONF_SLOT_3);
        if(!is_null($objConf)){
            $arrInfo = explode("#", $objConf->conf_content);
            if(count($arrInfo) >= 3){	//0-host, 1-ag_code, 2-ag_token
                $this->mHost = $arrInfo[0];
                $this->mAgCode = $arrInfo[1];
                $this->mAgToken = $arrInfo[2];
            }
        }
    }

    private function getHeader($post){

        return ['Content-Type: application/json',
            'Content-Length: ' . strlen($post),
            'Accept: */*'];
    }

    public function createUser($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'msg'=>CONNECT_ERROR);
        }

        $url = $this->mHost;
        
        $arrPost['method'] = "user_create";
        $arrPost['agent_code'] = $this->mAgCode;
        $arrPost['agent_token'] = $this->mAgToken;
        $arrPost['user_code'] = $id;
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                // "status": 1,
                // "msg": "SUCCESS",
                // "user_code": "Lu_1",
                // "user_slot_balance": 0,
                // "user_casino_balance": 0
            } else { 
                // "status": 0,
                // "msg": "DUPLICATE_USER"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }


        return $arrResult;
    }

    
    public function getUserInfo($id)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'msg'=>CONNECT_ERROR);
        }

        $url = $this->mHost;
        
        $arrPost['method'] = "money_info";
        $arrPost['agent_code'] = $this->mAgCode;
        $arrPost['agent_token'] = $this->mAgToken;
        $arrPost['user_code'] = $id;
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                // "status": 1,
                // "msg": "SUCCESS",
                // "agent": {
                //     "agent_code": "ace1",
                //     "slot_balance": 0,
                //     "casino_balance": 0
                // },
                // "user": {
                //     "user_code": "Lu_1",
                //     "slot_balance": 0,
                //     "casino_balance": 0
                // }
                $arrResult['balance'] = $arrResult['user']['slot_balance'];
            } else { //
                // "status": 0,
                // "msg": "DUPLICATE_USER"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }

        return $arrResult;
    }

    public function auth($id, $provider, $gameCode)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'msg'=>CONNECT_ERROR);
        }

        $url = $this->mHost;

        $arrPost['method'] = "game_launch";
        $arrPost['agent_code'] = $this->mAgCode;
        $arrPost['agent_token'] = $this->mAgToken;
        $arrPost['user_code'] = $id;
        $arrPost['game_type'] = "slot";
        $arrPost['provider_code'] = $provider;
        $arrPost['game_code'] = $gameCode;
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);
        
        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                // "status": 1,
                // "msg": "SUCCESS",
                // "launch_url": "http://kgame-tw1.pplaygame.net/game_start.do?mgckey=2f371beabbf9e12afdc708a219c17bf3&gameSymbol=vs20doghouse&lang=ko"
            } else { 
                // "status": 0,
                // "msg": "INVALID_PROVIDER_CODE"
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }


        return $arrResult;
    }


    public function addBalance($id, $balance)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'msg'=>CONNECT_ERROR);
        }
        
        $url = $this->mHost;
        
        $arrPost['method'] = "user_deposit";
        $arrPost['agent_code'] = $this->mAgCode;
        $arrPost['agent_token'] = $this->mAgToken;
        $arrPost['user_code'] = $id;
        $arrPost['amount'] = intval($balance);
        $arrPost['game_type'] = "slot";
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                // "status": 1, 
                // "msg": "success",
                // "agent_slot_balance": 990000,
                // "user_slot_balance": 10000,
                $arrResult['balance'] = $arrResult['user_slot_balance'];
            } else { //
                // "status": 0,
                // "msg": "INSUFFICIENT_FUNDS"
                writeLog("<Gslot> addBalance msg=".$arrResult['msg']);

            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }

        return $arrResult;
    }


    public function subBalance($id, $balance)
    {
        if(strlen($this->mHost) < 1){
            return array('status' => 0, 'msg'=>CONNECT_ERROR);
        }

        $url = $this->mHost;
        
        $arrPost['method'] = "user_withdraw";
        $arrPost['agent_code'] = $this->mAgCode;
        $arrPost['agent_token'] = $this->mAgToken;
        $arrPost['user_code'] = $id;
        $arrPost['amount'] = intval($balance);
        $arrPost['game_type'] = "slot";
        $post = json_encode($arrPost);

        $header =  $this->getHeader($post);

        $response = getCurlRequest($url, $header, $post);
        
        $arrResult = json_decode($response, true);
		
		if(!is_null($arrResult) && array_key_exists("status", $arrResult)) {
			if($arrResult['status'] == 1){
                // "status": 1,
                // "msg": "success"
                // "agent_slot_balance": 100000,
                // "user_slot_balance": 10000,        
                $arrResult['balance'] = $arrResult['user_slot_balance'];
            } else { //
                $arrResult['status'] = 0;
                // "status": 0,
                // "msg": "INSUFFICIENT_FUNDS" 
            }
		} else {
            $arrResult['status'] = 0;
            $arrResult['msg'] = CONNECT_ERROR;
        }
        return $arrResult;
    }

}