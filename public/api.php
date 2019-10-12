<?php

require '../vendor/autoload.php';

use Api\SitaApi_Airport;

$actions = [
    'weather' => [
        'class' => 'SitaApi_Weather',
        'method' => 'getWeather'
    ],
    'carbon' => [
        'class' => 'CarbonApi',
        'method' => 'getCarbonEmission'
    ],
];

$client = new \GuzzleHttp\Client();

$actionName = !empty($_REQUEST['action']) ? $_REQUEST['action'] : false;

if(!empty($actionName) && in_array($actionName, array_keys($actions))) {
    $action = $actions[$actionName];
    $className = '\Api\\' . $action['class'];
    if (class_exists($className) && method_exists($className, $action['method'])) {
        unset($_REQUEST['action']);
        $params = array_map(function($param){
            $parts = explode('_', $param);
            if (count($parts) == 2) {
                return $parts[0] . ucfirst($parts[1]);
            }
            return $param;
        }, $_REQUEST);
        $className::{$action['method']}($client, $params);
    }
}


//SitaApi_Airport::getAirports($client);