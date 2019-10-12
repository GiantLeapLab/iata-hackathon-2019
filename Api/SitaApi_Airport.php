<?php

namespace Api;

use GuzzleHttp\Client;

class SitaApi_Airport
{

    static public $apiKey = '3035d833bb6e531654a3cce03e6b1fde';

    public static function getAirports(Client $client)
    {
        $response = $client->request('GET', 'https://airport-qa.api.aero/airport/v2/airports',
            [
                'headers' => ['x-apikey' => self::$apiKey]
            ]
        );

        return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode());
    }

}