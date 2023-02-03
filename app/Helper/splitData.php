<?php

function splitData (String $data){
    $arrayData = explode('-',$data);
    return [
        "dia" => $arrayData[2],
        "mes" => $arrayData[1],
        "ano" => $arrayData[0]
    ];
}
