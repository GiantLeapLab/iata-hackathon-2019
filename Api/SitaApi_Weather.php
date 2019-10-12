<?php


namespace Api;
use GuzzleHttp\Client;

class SitaApi_Weather
{
    static public $apiKey = '89e15931434731aefdaa04920ec60e44';

    public static function getWeather(Client $client, $params)
    {

        $airportId = !empty($params['airportId']) ? $params['airportId'] : null;
        $startDate = !empty($params['startDate']) ? $params['startDate'] : null;

        if (!empty($airportId) && !empty($startDate)) {
            $response = $client->request('GET', 'https://weather-qa.api.aero/weather/v1/combined/'
                . $airportId
                . '?temperatureScale=C&duration=7&lengthUnit=K',
                [
                    'headers' => ['x-apikey' => self::$apiKey]
                ]
            );
            $weather = json_decode($response->getBody()->getContents(), true);
            $result = [
                'success' => false,
                'forecast' => [],
                'errors' => []
            ];

            if (isset($weather['success']) && $weather['success'] == true) {
                foreach ($weather['weatherForecast'] as $item) {
                    if ($item['forecastDate'] == $startDate) {
                        $result['forecast'] = $item;
                    }
                }

                if (empty($result)) {
                    $result['forecast'] = $weather['currentWeather'];
                }

                $result['forecast']['iconUrl'] = sprintf('uds-static.api.aero/weather/icon/lg/%s.png', $result['forecast']['icon']);
                $result['success'] = true;
                $result['errors'] = [];
            } else {
                $result['success'] = false;
                $result['errors'] = !empty($weather['errors']) ? $weather['errors'] : [];
            }

            return new JsonResponse($result, $response->getStatusCode());
        }

        return new JsonResponse([
            'success' => false,
            'errors' => 'Something went wrong'
        ], 500);
    }

}