<?php

namespace Api;

use GuzzleHttp\Client;

class CarbonApi
{

    public static function getCarbonEmission(Client $client, $params)
    {

        $depCode = !empty($params['depCode']) ? $params['depCode'] : null;
        $arrCode = !empty($params['arrCode']) ? $params['arrCode'] : null;
        $passengersAmount = !empty($params['passengersAmount']) ? $params['passengersAmount'] : null;

        if (!empty($depCode) && !empty($arrCode) && !empty($passengersAmount)) {
            $response = $client->request('GET', 'http://ec2-3-124-2-157.eu-central-1.compute.amazonaws.com:80/calculateflightemission?from='
                . $depCode
                . '&to='
                . $arrCode
                . '&passengers='
                . $passengersAmount . '&roundtrip=false&flightclass=economy'
            );

            return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode());
        }

        return new JsonResponse([
            'success' => false,
            'errors' => 'Something went wrong'
        ], 500);
    }

}