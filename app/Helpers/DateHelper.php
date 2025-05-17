<?php

function ethiopianToGregorian($ethiopianDate) {
    $year = substr($ethiopianDate, 0, 4);
    $month = substr($ethiopianDate, 5, 2);
    $day = substr($ethiopianDate, 8, 2);

    // Simple conversion logic
    $gregorianDate = \DateTime::createFromFormat('Y-m-d', "$year-$month-$day");
    return $gregorianDate->format('Y-m-d');
}
