<?php 

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
	$data = htmlspecialchars($data);	
    return $data;
}

function _sqlinsert($table,$data_array){
	// INSERT 
	if(!isset($conn)){
        require (DB_CONNECTION);
    }
	$sql  = "INSERT INTO $table";
	$sql .= " (".implode(", ", array_keys($data_array)).")";
	$sql .= " VALUES ('".implode("', '", $data_array)."')";
    $result = mysqli_query($conn,$sql);
	if ($result) {		
		return true; 
	}else{
		return false;
	}	    
}

function _sqlinsertid($table,$data_array){
	// INSERT AND GET NEW ID
	if(!isset($conn)){
        require (DB_CONNECTION);
    }
	$sql  = "INSERT INTO $table";
	$sql .= " (".implode(", ", array_keys($data_array)).")";
	$sql .= " VALUES ('".implode("', '", $data_array)."')";
    $result = mysqli_query($conn,$sql);
	if ($result) {
		$order_id = mysqli_insert_id($conn);
		return $order_id; 
	}else{
		return null;
	}	    
}

function _sqldelete($table,$where){
	// DELETE 
	if(!isset($conn)){
        require (DB_CONNECTION);
    }
	$sql  = "DELETE FROM $table WHERE $where";
	$result = mysqli_query($conn, $sql);	
	if ($result){	
		if(mysqli_affected_rows($conn) > '0'){			
			return true;
		}else{			
			return false;
		}		
		return false;
	}else{		
		return false;	
	}
	
}

function _sqlselectall($table,$where = null,$order = null){
	// SELECT
	if(!isset($conn)){
        require (DB_CONNECTION);
    }
	if ($where == "") {
		$where = null;
	}
	if ($order == "") {
		$order = null;
	}
	$sql  = "SELECT * FROM $table";
	$sql .= (isset($where))? " WHERE ".$where :"";
	$sql .= (isset($order))? " ORDER BY ".$order :"";
	$result = mysqli_query($conn,$sql);
	if (!$result or mysqli_num_rows($result) == 0){	
			
	}else{
		$arr_result = array();
		while ($row = mysqli_fetch_assoc($result)){
			$arr_result [] = $row;		
		}
		return $arr_result;
	}
	 
}
function _sqlcount($table,$where = null){
	// SELECT
	if(!isset($conn)){
        require (DB_CONNECTION);
	}
	$count = '0';
	if ($where == "") {
		$where = null;
	}	
	$sql  = "SELECT COUNT(*) as count FROM $table";
	$sql .= (isset($where))? " WHERE ".$where :"";	
	$result = mysqli_query($conn,$sql);
	if (!$result or mysqli_num_rows($result) == 0){	
			
	}else{
		
		while ($row = mysqli_fetch_assoc($result)){
			$count = $row['count'];		
		}
		
	}
	return $count;
}

function _sqlexecute($sql_query){
	// CUSTOM SQL
	if(!isset($conn)){
        if(!isset($conn)){
        require (DB_CONNECTION);
    }
    }
	
	$result = mysqli_query($conn,$sql_query);
	if (!$result){
		//null
		
	}else{
		
		if (is_bool($result)){
				return true;
			}else{
				if (mysqli_num_rows($result) == 0){
					//null
					
					}else{
						$arr_result = array();
						while ($row = mysqli_fetch_assoc($result)){
							$arr_result [] = $row;		
						}
						return $arr_result;
					}
			}
	}
	
}


function _sqlupdate($table,$data_array,$where){
	// UPDATE
	if(!isset($conn)){
        if(!isset($conn)){
        require (DB_CONNECTION);
    }
    }
	$cols = array(); 
	foreach($data_array as $key=>$val) {
		$cols[] = "$key = '$val'";
	}
	$sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE $where";

	$result = mysqli_query($conn, $sql);	
	if ($result){	
		return true;		
	}else{		
		return false;	
	}
	
}

function _sqlsetval($table,$column,$value,$where){
	// UPDATE WITH CONFIRM
	if(!isset($conn)){
        require (DB_CONNECTION);
    }
	
	$sql = "UPDATE $table SET $column = '$value'" . " WHERE $where";
	$result = mysqli_query($conn, $sql);	
	if ($result){		
		return true;				
	}else{		
		return false;	
	}
	
}

function _sqlgetval($table,$column,$where){
	// GET 1 value
	if(!isset($conn)){
        require (DB_CONNECTION);
    }	
	$sql = "SELECT $column FROM $table WHERE $where LIMIT 1";	
	$result = mysqli_query($conn,$sql);
	if (!$result or mysqli_num_rows($result) == 0){			
		return false;
	}else{		
		$row = mysqli_fetch_assoc($result);		
		return  $row[$column];		
	} 
	
}

function _sqlgetsum($table,$column,$where){
	// GET 1 value
	if(!isset($conn)){
        require (DB_CONNECTION);
    }	
	$sql = "SELECT SUM($column) AS 'total' FROM $table WHERE $where";	
	$result = mysqli_query($conn,$sql);
	if (!$result or mysqli_num_rows($result) == 0){		
		return false;
	}else{		
		$row = mysqli_fetch_assoc($result);		
		return  $row['total'];		
	} 	
}



function _send_mail($to,$subject,$body,$from=null){  
	
    if (isset($from)){
        $headers = "From: $from\n"; 
        $headers .= "Reply-To: $from";
    }else{
        $headers = "From: noreply@excenbit.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
    }   
    if (mail($to,$subject,$body,$headers)){                
        return true;    
    }else {//falha ao enviar email                    
        return false;
    }
}

function _sqlmax($table,$column,$where){//select MAX(db_amount) as max FROM table where;
	if(!isset($conn)){
        require (DB_CONNECTION);
    }	
	$sql = "SELECT MAX($column) AS 'max' FROM $table WHERE $where";	
	$result = mysqli_query($conn,$sql);
	if (!$result or mysqli_num_rows($result) == 0){		
		return false;
	}else{		
		$row = mysqli_fetch_assoc($result);		
		return  $row['max'];		
	} 	
	
}

function thousandsCurrencyFormat($num) {//format big number to bealtyful string
	if($num>1000) {
  
		  $x = round($num);
		  $x_number_format = number_format($x);
		  $x_array = explode(',', $x_number_format);
		  $x_parts = array('K', 'M', 'B', 'T');
		  $x_count_parts = count($x_array) - 1;
		  $x_display = $x;
		  $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
		  $x_display .= $x_parts[$x_count_parts - 1];  
		  return $x_display;  
	}else{
	  if($num>1) {
		$x_display = round($num);
		return $x_display;
	  }else{
		$x_display = round($num);
		return $x_display;
	  }
	}  
	
  }














?>



