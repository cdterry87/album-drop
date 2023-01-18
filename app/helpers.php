<?php

function getFormalDate($date)
{
    // Convert string date to DateTime object
    $date = new DateTime($date);

    // Return formatted date
    return $date->format('F j, Y');
}
