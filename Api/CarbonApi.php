<?php

namespace Api;

use GuzzleHttp\Client;

class CarbonApi
{

    public static function getCarbonEmission(Client $client, $depCode, $arrCode, $passengersAmount)
    {
        $response = $client->request('GET', 'http://ec2-3-124-2-157.eu-central-1.compute.amazonaws.com:80/calculateflightemission?from='
            . $depCode
            . '&to='
            . $arrCode
            . '&passengers='
            . $passengersAmount . '&roundtrip=false&flightclass=economy'
        );

        return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode());
    }

}