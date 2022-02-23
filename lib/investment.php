<?php

function isValid($date, $format){
  $dt = DateTime::createFromFormat($format, $date);
  return $dt && $dt->format($format) === $date;
}


Function check_data($data){
    $status = 'ok';
//verify keys
foreach ($data as $key => $value) {
  switch ($key){
    case 'name':
      if (strlen($value) < 3){
        $status = "name must be longer than 3 characters";       
      }
    break;
    case 'invest_date':
      $string = $value;
      $isvalid =isValid($string,'d/m/Y');
      if (!$isvalid){
        $status = "date must be in dd/mm/YYYY format ";
        break;
      }
      $date1	= new DateTime(gmdate("d-m-Y"));//today
      $date2 = DateTime::createFromFormat('d/m/Y', $string); 
      $diff = date_diff($date2,$date1);      
      $days = intval($diff->format("%R%a"));       
      if($days < 0){
        $status = "date should not be in the future";
        break;
      }
    break;
    case 'amount': 
      if(preg_match('/^[0-9.,]+$/', $value)){        
        $amount = floatval(str_replace(',', '.', str_replace('.', '', $value)));    
        if ($amount < 1){
          $status = "the minimum to invest amount is 1,00"; 
        break;       
        }
      }else{
        $status = "invalid amount";
      }
                             
    break;
      default:
        $status = "invalid field: ".$key; 
      break;
  }
} 
return $status;  
}

Function check_datew($strdate){
  $status = 'ok';
//verify keys

    $string = $strdate;
    $isvalid =isValid($string,'d/m/Y');
    if (!$isvalid){
      $status = "date must be in dd/mm/YYYY format ";
      return $status;
    }
    $date1	= new DateTime(gmdate("d-m-Y"));//today
    $date2 = DateTime::createFromFormat('d/m/Y', $strdate);
    $diff = date_diff($date2,$date1);      
    $days = intval($diff->format("%R%a"));       
    if($days < 0){
      $status = "date should not be in the future";
    }

  return $status;  
}
Function create_investment($data){

  $date = DateTime::createFromFormat('d/m/Y', $data['invest_date']);
  $newdate = $date->format('Y-m-d'); // => 2013-12-24
  $amount = floatval(str_replace(',', '.', str_replace('.', '', $data['amount'])));
  $data_array = array(
    'v_name' => $data['name'],
  'dt_creation_date' => $newdate,
  'db_amount' => $amount);

  $result = _sqlinsertid('projects_apiinvestment',$data_array);

  if (!$result){
    return false;
    
  }else{
    $newdate = $date->format('d/m/Y');
    $amountf = number_format($amount, 2, '.', '');
    $result = array(    
      'status' => 'ok',
      'name' => $data['name'],
      'creation date' => $newdate,
      'amount' => $amountf,
      'investment id' => $result
      );
    return $result;  
  }
}
Function taxation($amount,$dt_initial,$dt_final){
  
  $date1	= date_create($dt_initial);
  $date2 =  date_create($dt_final);   
  $diff=date_diff($date1,$date2);      
  $y = intval($diff->format("%y")); 
  $m = intval($diff->format("%m"));
  $months = 12 * intval($y) + intval($m);
  if ($y > 2){
    $current_tax = 0.15;
  }else if($y < 1){
    $current_tax = 0.225;
  }else{//1 or 2
    $current_tax = 0.185;
  }
  $str_wtax = ($current_tax*100).'%';
  $gain_monthly = 0.0052;//0.52%;
  $strgain = ($gain_monthly*100).'%';
  $amount = floatval($amount);  
  $acum_invest = $amount * ((1+$gain_monthly)**$months);
  $profit_total = $acum_invest - $amount;
  $total_tax = $profit_total * $current_tax;
  $profit_final = $profit_total - $total_tax;
  $acum_invest_final = $acum_invest + $profit_final; 
  $result = array(    
      'compound gain' =>$strgain,
      'months invested' => $months,
      'profit total' => number_format($profit_total, 2, '.', ''),
      'withdrawal tax' => $str_wtax,
      'total_tax' => number_format($total_tax, 2, '.', ''),
      'free_profit' => number_format($profit_final, 2, '.', ''),
      'amount total' => number_format($acum_invest_final, 2, '.', '')
      
      );
    return $result;  
  
}

?>  