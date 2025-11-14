<?php namespace App\Controllers;

class Sigma extends BaseController
{

	public function api($subUrl=""){
        
        $reqtData = file_get_contents("php://input");

        $headers = apache_request_headers();
        writeLog("[sigma_api] subUrl=".$subUrl." headers====");
        $headers = getallheaders();
        foreach ($headers as $name => $value) {
            writeLog("$name: $value");
        }
        writeLog("[sigma_api] subUrl=".$subUrl." headers==========");

        writeLog("[sigma_api] subUrl=".$subUrl." reqData=".$reqtData);

    
            $arrResult['code'] = RESULT_OK;			
            $arrResult['status'] = STATUS_SUCCESS;
    
        if($subUrl == "balance"){
            echo "1000";
        } else {

            echo json_encode($arrResult);
        }

    }

    public function req_agent(){

        $domain = "https://api.honorlink.org/api";
        $url = "$domain/my-info";

        writeLog("[sigma_req_agent] url=".$url);

        $Authorization = "htam1tzUpCcNb7mf0dKGFGxl341io2j3DhO9YgVYc0d83230";
        $header = ['Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer '.$Authorization
        ];
        $response = getCurlRequest2($url, $header);

        var_dump($response);
    }

}