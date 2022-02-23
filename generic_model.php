<?php
$doc_root = $_SERVER['DOCUMENT_ROOT'].'/api';
require_once($doc_root.('/config.php'));

//__________________________________________________________API generic____________________________
header("Access-Control-Allow-Origin: *");
$full_req_url = req_url();
$url_param_array = url_param($full_req_url);
$headers = getallheaders();
$req_method = $_SERVER['REQUEST_METHOD'];
switch ($req_method){
	case "GET": 
		response(405,'only POST method is alowed',NULL);
		return false; 
	break;
	case "POST": 
		//response(400,'only POST method is alowed',NULL);
		//return false;
	break;
	case "PUT": 
		response(405,'only POST method is alowed',NULL);
		return false;
 	break;
	case "DELETE": 
		response(405,'only POST method is alowed',NULL);
		return false;
 	break;

}
if (!empty($_GET)){// for all GET variables

}else{
	
} 
if (!empty($_POST)){// for all POST variable
	$data = json_decode(file_get_contents("php://input"));
	if($data === null) {
		// if is null because the json cannot be decoded		
		response(400,'invalid data',NULL);	

	}else{
		$arr_data = json_decode(json_encode($data),true);//stdclass to array
			
        //return data
		$json_response = json_encode($arr_data,JSON_UNESCAPED_UNICODE |JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);		
		echo $json_response;
	}
}else{
	response(400,'data not found',NULL);
}

//header( 'Retry-After: 5' );
//header("Location: https://google.com");
//header('Refresh: 10; url=https://google.com/');
//___________________________________________________________________________________________________




?>