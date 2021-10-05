<?php


function transfomrAmountToInt($amount)
{
    if (is_float($amount)) {
        return intval(multiply100($amount));
    }

    return $amount;
}

function multiply100($amount)
{
    return $amount * 100;
}

function divide100($amount)
{
    return $amount / 100;
}


function transfomrAmountToFloat($amount)
{
    if (is_int($amount)) {
        return floatval(divide100($amount));
    }

    return $amount;
}

function formatFloat($amount)
{
    if (is_float($amount)) {
        return number_format($amount, 2);
    }

    return $amount;
}

