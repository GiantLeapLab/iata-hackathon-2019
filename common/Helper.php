<?php

namespace common;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class Helper {

    public static $airports = [
        "FRA" => [
            "name" => "Frankfurt am Main International Airport",
            "city" => "Frankfurt",
            "country" => "Germany",
            "iata" => "FRA",
            "icao" => "EDDF",
            "latitude" => "50.0333333",
            "longitude" => "8.5705556",
            "altitude" => "364",
            "timezone" => "1",
            "dst" => "E"
        ],
        "AYT" => [
            "name" => "Antalya International Airport",
            "city" => "Antalya",
            "country" => "Turkey",
            "iata" => "AYT",
            "icao" => "LTAI",
            "latitude" => "36.898701",
            "longitude" => "30.800501",
            "altitude" => "177",
            "timezone" => "3",
            "dst" => "E"
        ],
        "ADB" => [
            "name" => "Adnan Menderes International Airport",
            "city" => "Izmir",
            "country" => "Turkey",
            "iata" => "ADB",
            "icao" => "LTBJ",
            "latitude" => "38.2924003601",
            "longitude" => "27.156999588",
            "altitude" => "412",
            "timezone" => "3",
            "dst" => "E"
        ]
    ];

    const DATE_FORMAT = 'M d, Y h:i a';
    const DATE_PART_DATE = 'M d, Y';
    const DATE_PART_TIME = ' h:i a';

    public static function formatDateTime($dateStr, $timeStr) {
        $parts['date'] = Carbon::createFromFormat('Y-m-d\Z H:i', $dateStr . ' ' . $timeStr)
            ->format(self::DATE_PART_DATE);
        $parts['time'] = Carbon::createFromFormat('Y-m-d\Z H:i', $dateStr . ' ' . $timeStr)
            ->format(self::DATE_PART_TIME);

        return $parts;
    }

    public static function formatDuration($durationStr) {
        $interval = new CarbonInterval(substr($durationStr, 0, strlen($durationStr)-5) . 'S');
        return $interval->forHumans(['short' => true]);
    }

    public static function getCityNameByAirportCode($code, $withCountry=null) {
        $res = self::$airports[$code]['city'];
        if ($withCountry) {
            $res .= ', ' . self::$airports[$code]['country'];
        }
        return $res;
    }
}