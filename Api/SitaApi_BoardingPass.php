<?php

namespace Api;

use GuzzleHttp\Client;

class SitaApi_BoardingPass
{

    static public $apiKey = '615656c7eef678e5a3c0e13382159557';

    public static function issueBoardingPass(Client $client, $email, $phone)
    {
        $response = $client->request('POST', 'http://dev2dbp.api.aero/api/14',
            [
                'headers' => [
                    'x-apikey' => self::$apiKey,
                    'Content-Type' => 'application/xml'
                ],
                'body' => <<<EOL
<Request LanguageCode="en" emailAddress="{$email}" mobileNumber="{$phone}" gate="212" boardingHHMM="18:40" departHHMM="19:10" arriveHHMM="20:30" ffMiles="" DepartTerminal="Term 2" ffTier="" message="Sample" CabinName="Economy">
    <Barcode firstName="TEST" lastName="TESTER" title="MR" formatCode="M" passengerDescription="1" sourceOfCheckIn="M" sourceOfBoardingPass="M" documentType="B" airlineDesignator="14" baggageTagNumber="85123456003">
        <Legs>
            <Leg bookingRef="P12345" depAirportCode="DUB" arrAirportCode="LHR" carrier="14" flightNumber="603" depDate="2017-05-21" classCode="Y" seatNumber="2A" seqNumber="2" passengerStatus="1" airlineNumericCode="14" ticketNumber="2300293153" selecteeIndicator="0" internationalDocVerification="1" marketingCarrier="14" ffAirline="14" ffNumber="123456789" IdAdIndicator="0" freeBaggageAllowance="2PC" airlineUseData=""></Leg>
        </Legs>
    </Barcode>
    <Parameters>
        <Parameter name="BagStatus">Checked </Parameter>
    </Parameters>
</Request>
EOL
            ]
        );

        return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode());
    }

    public static function updateBoardingPass(Client $client, $id, $email, $phone)
    {
        $response = $client->request('POST', 'https://dev2dbp.api.aero/api/ZZ',
            [
                'headers' => [
                    'x-apikey' => self::$apiKey,
                    'Content-Type' => 'application/xml'
                ],
                'body' => <<<EOL
<Request type="reissue" LanguageCode="en" id="{$id}" emailAddress="{$email}" mobileNumber="{$phone}" gate="212" boardingHHMM="18:40" departHHMM="19:10" arriveHHMM="20:30" ffMiles="" DepartTerminal="Term 2" ffTier="" message="Sample" CabinName="Economy">
    <Barcode firstName="Arie" lastName="van der Veek" title="MR" bookingRef="PNR123" depAirportCode="AMS" arrAirportCode="CDG" carrier="XS" flightNumber="56" depDate="2015-06-06" classCode="Y" seatNumber="21B" seqNumber="002" ffAirline="ZZ" ffNumber="123456789" ticketNumber="000123456789012" issuingCarrier="ZZ" />
</Request>
EOL
            ]
        );

        return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode());
    }

}