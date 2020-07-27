<?php

use Carbon\Carbon;

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

function formatDisplayDate($date)
{
    $parsed_date = Carbon::parse($date);

    return $parsed_date->setTimezone('GMT+8')->isToday() ?  $parsed_date->format('F d, Y') . " (Today)" :  $parsed_date->format('F d, Y (l)');
}

function getDaysInBetween($from = NULL, $to = NULL)
{
    $from = isset($from) ? $from  : now();
    $to = isset($to) ? $to  : now();

    $dates = collect([]);

    for ($date = $from; $date->lte($to->copy()); $date->addDay()) {
        $dates->add($date->format('M d, Y'));
    }

    return $dates->reverse();
}