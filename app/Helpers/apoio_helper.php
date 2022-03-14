<?php
function show_array($data, $exit = true)
{

    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($exit) {
        exit;
    }
}

/**
 * @param $json
 * @param int $httpCode
 * @return void
 */
function responseJson($json, int $httpCode = 200) :void
{
    $content = json_encode($json, JSON_PRETTY_PRINT);
    header('content-type: application/json; charset: utf-8');
    http_response_code($httpCode);
    ob_start((true ? 'ob_gzhandler' : null));
    echo $content;
    ob_end_flush();
    $content = null;
    exit();
}
function validateDate($date, $format = 'Y-m-d'): bool
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
function moneyReal($valor)
{
    $retorno = number_format($valor, 2, ",", ".");
    return $retorno;
}
function getToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return strtoupper($token);
}

function diffDates($date_1,$date_2, $format = 'Y-m-d'): array
{
    $date1 = date_create_from_format($format, $date_1);
    $date2 = date_create_from_format($format, $date_2);
    return (array) date_diff($date1, $date2);
}
function calcJurosCompostos($capital,$taxa, $meses)
{
    return $capital * pow((1 + $taxa), $meses);
}