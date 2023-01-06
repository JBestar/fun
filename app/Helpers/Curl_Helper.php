<?php

function getCurlRequest($url, $headers = null, $post = null){
    
    $timeout = 3;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    if(substr($url, 0, 5) == 'https'){
        curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    }
    else
    {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    }
    if ($post && !empty($post)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
    }
    if ($headers && !empty($headers)) {
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);

    $response = curl_exec($curl);

    $result['error'] = "";
    if (curl_errno($curl)) {        
        $result['error'] = curl_error($curl);
        return "";            
    }

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    $result['header'] = substr($response, 0, $header_size);
    $result['body'] = substr( $response, $header_size );

    curl_close($curl);

    return $result['body'];


}


function writeLog($contenet){ 
    
    if(!LOG_WRITE)
        return;

    $tmNow = time() ;
    $nHour = date("G",$tmNow);
    $nMin = date("i",$tmNow);
    $nSec = date("s",$tmNow);

    $sDate = date( 'Y-m-d', $tmNow);
    $fLog = fopen(LOG_FILE.$sDate, "a") ;

    $tContent = "[".$nHour.":".$nMin.":".$nSec."] ".$contenet."\r\n";

    fputs($fLog, $tContent);
    fclose($fLog);
}

?>