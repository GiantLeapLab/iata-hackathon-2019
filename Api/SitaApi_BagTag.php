<?php

namespace Api;

use GuzzleHttp\Client;

class SitaApi_BagTag
{

    static public $apiKey = 'c4a1450dd473e9bde54339fc6755bd50';

    public static function getBagTag(Client $client)
    {
        $response = $client->request('POST', 'http://devbagtag.us-east-1.elasticbeanstalk.com/bagtag/v1/issue',
            [
                'headers' => [
                    'x-apikey' => self::$apiKey,
                    'Content-Type' => 'application/xml'
                ],
                'body' => <<<EOL
<request languageCode="en">
    <flightDetails departDate="YYYYMMDD" departHHMM="19:10" arriveHHMM="20:30" departure="DUB" arrival="IST" flightNumber="212" channel="BAG">
        <leg depAirportCode="DUB" arrAirportCode="LHR" flightNumber="123" />
        <leg depAirportCode="LHR" arrAirportCode="GVA" flightNumber="345" />
        <leg depAirportCode="NYC" arrAirportCode="IST" flightNumber="678" />
    </flightDetails>
    <bagTags>
        <bagTag bookingRef="PNR1253" boardingNumber="OutOfScope" passengerItineraryID="Optional">
            <passengers>
                <passenger>
                    <bagIdentifiers>
                        <bagIdentifier LPN="12345678" uniqueData="????" />
                        <bagIdentifier LPN="" uniqueData="????" />
                    </bagIdentifiers>
                    <firstName>TEST</firstName>
                    <lastName>TESTER</lastName>
                    <title>MR</title>
                    <address>
                        <name></name>
                        <street>Optional</street>
                        <city>Optional</city>
                        <state>Optional</state>
                        <country>Optional</country>
                    </address>
                </passenger>
            </passengers>
        </bagTag>
    </bagTags>
</request>
EOL
            ]
        );

        return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode());
    }

}