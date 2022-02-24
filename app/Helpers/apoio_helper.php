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
function isMobileHelper() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
function moneyReal($valor)
{
    $retorno = number_format($valor, 2, ",", ".");

    return $retorno;

}
function textoLimite($string,$maxCharacter)
{
    return mb_strimwidth($string,0,$maxCharacter,'...');

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
function isEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return true;
    }
    return false;
}
function responseJson($json, $httpCode = 200)
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

function viewDevice()
{
    if (isMobileHelper()):
        return "_mobile";
    else:
        return "_desktop";
    endif;

}
function validPhone($phone): bool
{

    $regex = '/^(?:(?:\+|00)?(55)\s?)?(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})\-?(\d{4}))$/';
    if (preg_match($regex, $phone) == false){
        return false;
    }else{
        return true;
    }
}
function brazilianPhoneParser(string $phoneString, bool $forceOnlyNumber = true) : ?array
{
    $phoneString = preg_replace('/[()]/', '', $phoneString);
    if (preg_match('/^(?:(?:\+|00)?(55)\s?)?(?:\(?([0-0]?[0-9]{1}[0-9]{1})\)?\s?)??(?:((?:9\d|[2-9])\d{3}\-?\d{4}))$/', $phoneString, $matches) === false) {
        return null;
    }

    $ddi = $matches[1] ?? '';
    $ddd = preg_replace('/^0/', '', $matches[2] ?? '');
    $number = $matches[3] ?? '';
    if ($forceOnlyNumber === true) {
        $number = preg_replace('/-/', '', $number);
    }

    return ['ddi' => $ddi, 'ddd' => $ddd , 'number' => $number];
}

function slugThis($str)
{
    $str = mb_strtolower($str); //Vai converter todas as letras maiúsculas pra minúsculas
    $str = preg_replace('/(â|á|ã)/', 'a', $str);
    $str = preg_replace('/(ê|é)/', 'e', $str);
    $str = preg_replace('/(í|Í)/', 'i', $str);
    $str = preg_replace('/(ú)/', 'u', $str);
    $str = preg_replace('/(ó|ô|õ|Ô)/', 'o',$str);
    $str = preg_replace('/(_|\/|!|\?|#)/', '',$str);
    $str = preg_replace('/( )/', '-',$str);
    $str = preg_replace('/ç/','c',$str);
    $str = preg_replace('/(-[-]{1,})/','-',$str);
    $str = preg_replace('/(,)/','-',$str);
    $str=strtolower($str);
    return $str;
}