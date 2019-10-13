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

            $res = json_decode($response->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
        } else {
            $res = [];
        }

        include(TPL_PATH . '/co.php');
    }

}