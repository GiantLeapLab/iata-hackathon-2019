<?php

require '../vendor/autoload.php';

use Api\SitaApi_Airport;

$client = new \GuzzleHttp\Client();

SitaApi_Airport::getAirports($client);