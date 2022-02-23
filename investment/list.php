<?php
$doc_root = $_SERVER['DOCUMENT_ROOT'].'/api';
require_once($doc_root.('/config.php'));
$full_req_url = req_url();
$url_param_array = url_param($full_req_url);
$req_method = $_SERVER['REQUEST_METHOD'];
header("Access-Control-Allow-Origin: *");
switch ($req_method){
	case "GET": 
		response(405,'only POST method is alowed',NULL);
		return false; 
	break;
	case "POST": 
		//response(400,'only GET method is alowed',NULL);
		//return false;
	break;
	case "PUT": 
		response(405,'only GET method is alowed',NULL);
		return false;
 	break;
	case "DELETE": 
		response(405,'only GET method is alowed',NULL);
		return false;
 	break;

}

if (!empty($_POST)){// for all POST variable
	$data = json_decode(file_get_contents("php://input"));
	if($data === null) {
		// if is null because the json cannot be decoded		
		response(400,'invalid format',NULL);	

	}else{
		$arr_data = json_decode(json_encode($data),true);//stdclass to array

		if (isset($arr_data['name'])){
			$name = $arr_data['name'];
			//optional pagination
			if (!isset($arr_data['page'])){
				$page = 1;
			}else{
				$page = $arr_data['page'];
				if(!preg_match('/^[0-9]+$/', $page)){		  
					response(400,'data page number',NULL);
					return false;
				}
			}
			$maxdisplay = 10;
			if ($page< 1){
				$page = 1;
			}
			$page = ($page*10)-10;
			$sql = "SELECT * FROM projects_apiinvestment WHERE v_name = '$name' ORDER BY dt_creation_date ASC LIMIT $page, $maxdisplay";
			$data_array = _sqlexecute($sql);
			if (empty($data_array)){
				//no register found
				echo 'name not found';
			}else{
				$result = array();
				//prepare data
				foreach ($data_array as $key => $value) {
					$status = ($data_array[$key]['ti_withdrawn'] == '1')? 'paid' :'active';
					$result[$key]['investment id'] = intval($data_array[$key]['id']);
					$result[$key]['name'] = $data_array[$key]['v_name'];
					$result[$key]['amount'] = number_format($data_array[$key]['db_amount'], 2, '.', '');
					$result[$key]['date of creation'] = $data_array[$key]['dt_creation_date'];
					$result[$key]['status'] = $status;
					$result[$key]['date of withdrawal'] = $data_array[$key]['dt_withdraw'];
					$result[$key]['withdrawn amount'] = number_format($data_array[$key]['db_amount_withdrawn'], 2, '.', '');
				}

				//return data				

				$json_response = json_encode($result,JSON_UNESCAPED_UNICODE |JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);		
				echo $json_response;
			}
		}else{
			response(400,'missing parameters',NULL);
		}
	}
}else{
	response(400,'missing parameters',NULL);
}

//___________________________________________________________________________________________________




//header( 'Retry-After: 5' );
//header("Location: https://google.com");
//header('Refresh: 10; url=https://google.com/');


?>