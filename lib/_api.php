<?php
// Function to display current requested URL.

Function url_param($full_req_url){
  $url_components = parse_url($full_req_url); 
  // Use parse_str() function to parse the
  // string passed via URL
  if (isset($url_components['query'])){
    parse_str($url_components['query'], $params);
  return $params;
  }else{
    return false;
  }
  
}
Function req_url(){
  $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] 
  === 'on' ? "https" : "http") . 
  "://" . $_SERVER['HTTP_HOST'] . 
  $_SERVER['REQUEST_URI'];
  return $link;
}

function response(int $httpcode,$message,$data){

	http_response_code($httpcode); 
  switch ($httpcode) {
    case 100: $error_status = 'Continue'; break;
    case 101: $error_status = 'Switching Protocols'; break;
    case 200: $error_status = 'OK'; break;
    case 201: $error_status = 'Created'; break;
    case 202: $error_status = 'Accepted'; break;
    case 203: $error_status = 'Non-Authoritative Information'; break;
    case 204: $error_status = 'No Content'; break;
    case 205: $error_status = 'Reset Content'; break;
    case 206: $error_status = 'Partial Content'; break;
    case 300: $error_status = 'Multiple Choices'; break;
    case 301: $error_status = 'Moved Permanently'; break;
    case 302: $error_status = 'Moved Temporarily'; break;
    case 303: $error_status = 'See Other'; break;
    case 304: $error_status = 'Not Modified'; break;
    case 305: $error_status = 'Use Proxy'; break;
    case 400: $error_status = 'Bad Request'; break;
    case 401: $error_status = 'Unauthorized'; break;
    case 402: $error_status = 'Payment Required'; break;
    case 403: $error_status = 'Forbidden'; break;
    case 404: $error_status = 'Not Found'; break;
    case 405: $error_status = 'Method Not Allowed'; break;
    case 406: $error_status = 'Not Acceptable'; break;
    case 407: $error_status = 'Proxy Authentication Required'; break;
    case 408: $error_status = 'Request Time-out'; break;
    case 409: $error_status = 'Conflict'; break;
    case 410: $error_status = 'Gone'; break;
    case 411: $error_status = 'Length Required'; break;
    case 412: $error_status = 'Precondition Failed'; break;
    case 413: $error_status = 'Request Entity Too Large'; break;
    case 414: $error_status = 'Request-URI Too Large'; break;
    case 415: $error_status = 'Unsupported Media Type'; break;
    case 500: $error_status = 'Internal Server Error'; break;
    case 501: $error_status = 'Not Implemented'; break;
    case 502: $error_status = 'Bad Gateway'; break;
    case 503: $error_status = 'Service Unavailable'; break;
    case 504: $error_status = 'Gateway Time-out'; break;
    case 505: $error_status = 'HTTP Version not supported'; break;  
    default:
        $error_status = "Undocumented error "; break;
}
 
    //header($http[$code]);
    
    $response =     array(
            'code' => $httpcode,
            'status' => $error_status,
            'message' => $message,
            'data' => $data,
    );

	$json_response = json_encode($response,JSON_UNESCAPED_UNICODE |JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	echo $json_response;
}



?>