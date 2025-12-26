<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function distance_from_office($lat1, $lon1, $lat2, $lon2)
{
    if (empty($lat1) || empty($lon1)) {
        return null;
    }

    $earthRadius = 6371000; // meters

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return round($earthRadius * $c); // meters
}