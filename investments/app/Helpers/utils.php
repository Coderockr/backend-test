<?php


function transfomrAmountToDB($mount)
{
    return $mount * 100;
}

function transfomrAmountFromDB($mount)
{
    return $mount / 100;
}

