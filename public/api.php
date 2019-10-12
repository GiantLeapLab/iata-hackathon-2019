<?php

require '../vendor/autoload.php';

use Api\SitaApi_Airport;

define('ROOT_PATH', dirname(__FILE__) . '/..');

$actions = [
    'weather' => [
        'class' => 'SitaApi_Weather',
        'method' => 'getWeather'
    ],
    'carbon' => [
        'class' => 'CarbonApi',
        'method' => 'getCarbonEmission'
    ],
    'search' => [
        'class' => 'SunExpress_Api',
        'method' => 'basicOneWaySearchCached'
    ]
];

$client = new \GuzzleHttp\Client(['verify' => false]);

$actionName = !empty($_REQUEST['action']) ? $_REQUEST['action'] : false;

if(!empty($actionName) && in_array($actionName, array_keys($actions))) {
    $action = $actions[$actionName];
    $className = '\Api\\' . $action['class'];
    if (class_exists($className) && method_exists($className, $action['method'])) {
        unset($_REQUEST['action']);
        $params = [];
        foreach ($_REQUEST as $key=>$value) {
            $parts = explode('_', $key);
            if (count($parts) == 2) {
                $params[$parts[0] . ucfirst($parts[1])] = $value;
            } else {
                $params[$key] = $value;
            }
        }

        $className::{$action['method']}($client, $params);
    }
}


//SitaApi_Airport::getAirports($client);