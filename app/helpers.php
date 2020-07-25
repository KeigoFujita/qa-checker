<?php


function padZero($num, $len)
{
    $str = $num;
    while (strlen($str) < $len) {
        $str = '0' . $str;
    }
    return $str;
}

function formatTimestamp($seconds)
{
    $minutes = ($seconds / 60.0) | 0;
    $seconds %= 60;
    $seconds |= 0;
    return $minutes . ':' . padZero($seconds, 2);
}

function convertToPeso($dollar)
{
    return number_format($dollar * 48.0, 2);
}