<?php
	
	  //자료기지 접속
	function connectDb($arrConfig){

	    $strDbHost="";
	    if(array_key_exists('db_host', $arrConfig)){
	        $strDbHost=$arrConfig['db_host'];
	    }
	      
	    $strDbUser="";
	    if(array_key_exists('db_user', $arrConfig)){
	        $strDbUser=$arrConfig['db_user'];
	    }
	      
	    $strDbPwd="";
	    if(array_key_exists('db_pwd', $arrConfig)){
	        $strDbPwd=$arrConfig['db_pwd'];
	    }

	    $strDbName="";
	    if(array_key_exists('db_name', $arrConfig)){
	        $strDbName=$arrConfig['db_name'];
	    }

	    $dbConn= new mysqli($strDbHost, $strDbUser, $strDbPwd, $strDbName);
	    
	    return $dbConn;
	}
	
	function getCurl($url, $headers = null, $post = null){
    
		$timeout = 5;
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		// if(substr($url, 0, 5) == 'https'){
		// 	curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// }
		// else
		// {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// }
		if (!is_null($post)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}
		if (!is_null($headers)) {
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout*2);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
	
		return $curl;
	}
		
	function getCurlWithProxy($url, $proxyUrl="", $headers = null, $post = null){
    
		$timeout = 5;
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		// if(substr($url, 0, 5) == 'https'){
		// 	curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// }
		// else
		// {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// }

		if( strlen($proxyUrl) > 10 ) {

			$proxyType=CURLPROXY_SOCKS5;
			$proxyAuth = "jmaster:startend";
			// Specify proxy type 
			curl_setopt($curl, CURLOPT_PROXYTYPE, $proxyType);
			// Set proxy server and port
			curl_setopt($curl, CURLOPT_PROXY, $proxyUrl);
			// Optional: Proxy authentication
			curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxyAuth);
		}
		
		if (!is_null($post)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}
		if (!is_null($headers)) {
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout*2);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
	
		return $curl;
	}

	function curlProc(&$hMul, $fLog){
		
		$result = null;
		$logHead = "<MultiCurl>";

		curl_multi_exec($hMul, $running);
		curl_multi_select($hMul);

		if ($state = curl_multi_info_read($hMul)) {
			
			$content = curl_multi_getcontent($state['handle']);
			// writeLog($fLog, $logHead.json_encode($content));
			
			$headerSize = curl_getinfo($state['handle'], CURLINFO_HEADER_SIZE);
			// writeLog($fLog, $logHead.$headerSize);
			// $result['header'] = substr($response, 0, $header_size);
			$result = substr( $content, $headerSize );
			
			curl_multi_remove_handle($hMul, $state['handle']);
			curl_multi_close($hMul);
			$hMul = null;
		}

		// writeLog($fLog, $logHead.$hMul."=".$running);
		return $result;
	}

	function curlProc2(&$hMul, $fLog){
		
		$result = null;
		$logHead = "<MultiCurl>";

		curl_multi_exec($hMul, $running);
		curl_multi_select($hMul);

		if ($state = curl_multi_info_read($hMul)) {
			
			$content = curl_multi_getcontent($state['handle']);
			// writeLog($fLog, $logHead.json_encode($content));
			
			$result['code'] = curl_getinfo($state['handle'], CURLINFO_HTTP_CODE);
			if (curl_errno($state['handle'])) {        
				$result['error'] = curl_error($state['handle']);
			}
			$headerSize = curl_getinfo($state['handle'], CURLINFO_HEADER_SIZE);
			// writeLog($fLog, $logHead.$headerSize);
			// $result['header'] = substr($response, 0, $header_size);
			$result['body'] = substr( $content, $headerSize );
			
			curl_multi_remove_handle($hMul, $state['handle']);
			curl_multi_close($hMul);
			$hMul = null;
		}

		// writeLog($fLog, $logHead.$hMul."=".$running);
		return $result;
	}

	function getCurlRequest($url, $headers = null, $post = null){
    
		$timeout = 5;
	
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
		if (!is_null($post)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		}
		if (!is_null($headers)) {
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
	
		//echo "Header:".$header_size."\r\n";

		curl_close($curl);
	
		return $result['body'];
	
	
	}

	function getCurlRequestWithProxy($url, $proxyUrl="", $headers = null, $post = null, $customRequest = null, $timeout = 20){
    
		$timeoutConnect = 5;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);

		// if(substr($url, 0, 5) == 'https'){
		// 	curl_setopt($curl, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
		// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		// }
		// else
		// {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// }

		if( strlen($proxyUrl) > 10 ){
			$proxyType=CURLPROXY_SOCKS5;
			$proxyAuth = "jmaster:startend";
			// Specify proxy type 
			curl_setopt($curl, CURLOPT_PROXYTYPE, $proxyType);
			// Set proxy server and port
			curl_setopt($curl, CURLOPT_PROXY, $proxyUrl);
			// Optional: Proxy authentication
			curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxyAuth);
		}
		
		if(!is_null($post)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		} 

		if($headers && !empty($headers)) {
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}
		if($customRequest && !empty($customRequest)) {
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $customRequest);
		}

		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeoutConnect);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		
		$response = curl_exec($curl);

		$result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$result['error'] = "";
		if (curl_errno($curl)) {        
			$result['error'] = curl_error($curl);
		}

		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		// $result['header'] = substr($response, 0, $header_size);
		$result['body'] = substr( $response, $header_size );

		curl_close($curl);

		return $result;
	}

?>