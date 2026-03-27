<?php
function convertTimes($timestamp)
{
    $miladi_date = date('Y-m-d', $timestamp);
    $shamsi_date = jdate('Y/m/d', $timestamp);
    return [
        'miladi' => $miladi_date,
        'shamsi' => $shamsi_date
    ];
}
