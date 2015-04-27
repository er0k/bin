#!/usr/bin/php
<?php

// philly
$lat = 39.95;
$lon = -75.1667;

$now = time();
$dtz = new DateTimeZone('America/New_York');
$dt = new DateTime('now', $dtz);
$offsetInSeconds = $dtz->getOffset($dt);
$offset = $offsetInSeconds / 60 / 60;

$timesRaw = array(
    'sunrise' => date_sunrise($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 90 + (50 / 60), $offset),
    'sunset' => date_sunset($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 90 + (50 / 60), $offset),
    'civil_twilight_begin' => date_sunrise($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 96, $offset),
    'civil_twilight_end' => date_sunset($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 96, $offset),
    'nautical_twilight_begin' => date_sunrise($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 102, $offset),
    'nautical_twilight_end' => date_sunset($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 102, $offset),
    'astronomical_twilight_begin' => date_sunrise($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 108, $offset),
    'astronomical_twilight_end' => date_sunset($now, SUNFUNCS_RET_TIMESTAMP, $lat, $lon, 108, $offset)
);

$times = array_map('fart', $timesRaw);

function fart($timestamp) {
    return date('H:i:s', $timestamp);
}

if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'rise':
            echo date('Hi', $timesRaw['sunrise']);
            break;
        case 'set':
            echo date('Hi', $timesRaw['sunset']);
            break;
        default:
            exit();
    }
    echo "\n";
} else {
    foreach ($times as $name => $time) {
        echo sprintf("%s: %s\n", $name, $time);
    }
}
