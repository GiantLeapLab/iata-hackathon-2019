<?php

namespace Api;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class SunExpress_Api {

    public static $userName = 'HKTHONUSR';
    public static $password = 'Hack@thonuser123';
    public static $url = 'https://iflyrestest.ibsgen.com:6013/iRes_NdcRes_WS/services/NdcResService172SOAPPort?wsdl';

    public static $cardNumber  = 'NxKj9GDLjCEO1ZV9Kd6ezl1PnhwFO6AnvPmBoRAbn48=%~~`%~~~~~~~%^**(%$#%am1dxNXNzPTDRXdd4D3vFEtNVxcZrFbIQkHcdXJjKxAMvJIUk+SUuR2mIdO4goIbCXn1Eek5H3KCE87lvqylJri1PEgGwyPE4DZ1iZuQaIPRwB94jEEDs+vaAuMDNt0hxQN2zdyFHe7VbR6BywjMajalWc5e32oY1V3Ad4xn2/YYAsRVcFOulyZP2HZpJVZHBXnRhp+tNjI4ivyeAGs9WmPL+eiyiS3gNHQeZ5FjlIuNdg1Wr9RnA1jUbEAmtTcShRnz2Tzo/ysgs0RRqVE9tN0HCXEBS9LxgRnwBFT1pvTQQQp6CLlPlCihQdx5Pfb0/ShXczenhDsd4j8lkMdCAA==';
    public static $seriesCode = 'oFSfEOeDdUDep3UC+SvRpw==%~~`%~~~~~~~%^**(%$#%Fw8/F/i7NC7bykWPhW9Uil4GiHXN62g1PTvKgdiUGe2GvWh2pOaMZCcM9bQX/XZfBmwyKfeiIdsu6qgmT0slguooa5X7q2HoMDq7Ox6kjawX744v9+rUhg4A/nmXe+4IboT5JEu2B4inoMPiLXJLpEOh/4oV3V2QBO32cic9az/5m09s5RNx2RB9bMdK4qNsuSLT1U8RaWr0QC76OD/c/8nO4oCxIe2E0ctiene6gONwbgVRyzOCyDV8/feRaoTJFzqZAodIz381GmMk+6at2UIPzn0b7AJ1Arix1/H2OVuSFRmBIceM28z0GPeq5oUsTd8pxCuVDFbUPdHwk6A4DA==';
    public static $cardHolderName = 'v3uNb9+FkDmirVsRgiLF5g==%~~`%~~~~~~~%^**(%$#%bDS+MHp5qKwtg80/Nf5JNrRXGyAuwH6SVAh9VJL4BR5qoq1h2URk2SVh2N8nLDBeV9FP/WReR+l4zzxzOlmcrXUeocDEMguce5rWXI1/msazqFmqxMlgseoPVm7iStFJ6h1bLJtk2gvHPAnDxlzQ9+UDERj0XQw/nTahLkMbxr52NwrkM3fjgRh/+2cNws2RW3Vsub3Fv9nRRADjLkDJfoLG+h7RDhbqV0CYQ9ZIkvCDo43Hs6bjmHdKD4MZoCtPo06qGx71h+CrZEGYe7/WVRE257A/V8+FhNmt0zPlmS3cNw8So3Kbzr4/HlInAx63ZIWni8aTkQmG3+ZOrP7FWw==';
    public static $expiration = '5c1NTE8bR1YLrMXWSSH0wA==%~~`%~~~~~~~%^**(%$#%Jn7/bfhgzMSH0tlv5AisOVzTen/K8NxOBY99w8hXWUu0kkGhs7zmpdnUECOd4VCNmv9FQWojQIoFLu3C3YKSxE5WZaIdy6UtarB1k20XyjdZjNwT4yiK+iQBzLqv9XRgMgRkrF78gN0IQvXrR8p8pcwKamJhqkyaG4L0ahAPoyKM1lTSVY/buu9gM9PW8tPudR8QqKt3Ow8imcekGiiPrpediq5RxYI44A3zPQf5CPFVwH70FjZ4dstd361m4dIPWCaEZLB0ZEDqgcoXK7d1oLrkV7/JSknR+10AK7PNl84OneiQZpN88WEUnYaxNJ8IWC2DiliJdRrnhdne2BTwjg==';

    public static function basicOneWaySearch(Client $client, $params) {
        $depCode = !empty($params['depCode']) ? $params['depCode'] : null;
        $arrCode = !empty($params['arrCode']) ? $params['arrCode'] : null;
        $depDate = !empty($params['depDate']) ? $params['depDate'] : null;
        $arrDate = !empty($params['arrDate']) ? $params['arrDate'] : null;

        $response = $client->request('POST', self::$url,
            [
                'headers' => [
                    'username' => self::$userName,
                    'password' => self::$password,
                    'Content-Type' => 'application/xml'
                ],
                'body' => <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns="http://www.iata.org/IATA/EDIST/2017.2">
   <soapenv:Header/>
   <soapenv:Body>
      <ns:AirShoppingRQ>
         <!--Optional:-->
         <ns:PointOfSale>
            <!--Optional:-->
            <ns:Location>
               <!--Optional:-->
               <ns:CountryCode>DE</ns:CountryCode>
               <!--Optional:-->
               <ns:CityCode>FRA</ns:CityCode>
            </ns:Location>
            <!--Optional:-->
            <ns:RequestTime Zone="CET">2018-01-02T07:01:00</ns:RequestTime>
         </ns:PointOfSale>
         <ns:Document>
            <ns:Name>NDC</ns:Name>
            <!--Optional:-->
            <ns:ReferenceVersion>17.2</ns:ReferenceVersion>
         </ns:Document>
         <ns:Party>
            <ns:Sender>
               <!--You have a CHOICE of the next 10 items at this level-->
               <ns:AgentUserSender>
                  <ns:Name>HKTHONUSR</ns:Name>
                  <ns:AgentUserID>HKTHONUSR</ns:AgentUserID>
               </ns:AgentUserSender>
            </ns:Sender>
            <!--Optional:-->
            <ns:Recipient>
               <ns:ORA_Recipient>
                  <ns:AirlineID>XQ</ns:AirlineID>
                  <!--Optional:-->
                  <ns:Name>Sun Express</ns:Name>
               </ns:ORA_Recipient>
            </ns:Recipient>
         </ns:Party>
         <ns:CoreQuery>
            <!--You have a CHOICE of the next 4 items at this level-->
            <ns:OriginDestinations>
               <!--1 or more repetitions:-->
               <ns:OriginDestination>
                  <ns:Departure>
                     <ns:AirportCode>{$depCode}</ns:AirportCode>
                     <ns:Date>{$depDate}</ns:Date>
                  </ns:Departure>
                  <ns:Arrival>
                     <ns:AirportCode>{$arrCode}</ns:AirportCode>
                  </ns:Arrival>
                  <ns:CalendarDates DaysBefore="0" DaysAfter="0"/>
               </ns:OriginDestination>
               <ns:OriginDestination>
                  <ns:Departure>
                     <ns:AirportCode>{$arrCode}</ns:AirportCode>
                     <ns:Date>{$arrDate}</ns:Date>
                  </ns:Departure>
                  <ns:Arrival>
                     <ns:AirportCode>{$depCode}</ns:AirportCode>
                  </ns:Arrival>
                  <ns:CalendarDates DaysBefore="0" DaysAfter="0"/>
               </ns:OriginDestination>
            </ns:OriginDestinations>
         </ns:CoreQuery>
         <ns:DataLists>
            <ns:PassengerList>
               <ns:Passenger PassengerID="T1">
                  <ns:PTC>ADT</ns:PTC>
               </ns:Passenger>
            </ns:PassengerList>
         </ns:DataLists>
      </ns:AirShoppingRQ>
   </soapenv:Body>
</soapenv:Envelope>

XML
            ]
        );

       $crawler = new Crawler();
       $crawler->addXmlContent($response->getBody()->getContents());
       $data = [];

       $dataListData = $crawler->filterXPath('descendant-or-self::soap:Body/ns2:AirShoppingRS/ns2:DataLists/ns2:FlightSegmentList');

       $crawler->filterXPath('descendant-or-self::soap:Body/ns2:AirShoppingRS/ns2:OffersGroup/ns2:AirlineOffers/ns2:Offer')->each(function (Crawler $node) use (&$data, $dataListData) {

           $node->filterXPath('descendant-or-self::ns2:OfferItem')->each(function (Crawler $itemNode) use (&$data, $dataListData) {


               $costAmount = $itemNode->filterXPath('descendant-or-self::ns2:TotalPriceDetail/ns2:TotalAmount/ns2:DetailCurrencyPrice/ns2:Total')->text();
               $currencyArr = $itemNode->filterXPath('descendant-or-self::ns2:TotalPriceDetail/ns2:TotalAmount/ns2:DetailCurrencyPrice/ns2:Total')->extract(['Code']);

               $cost = $costAmount . ' ' . $currencyArr[0];

               $flightData = [];
               $itemNode->filterXPath('descendant-or-self::ns2:FareDetail/ns2:FareComponent/ns2:SegmentRefs')
                   ->each(function (Crawler $segmentRefNode) use ($dataListData, &$flightData) {

                       $segmentsIdString = $segmentRefNode->text();
                       $segmentsIdList = explode(' ', $segmentsIdString);

                       foreach ($segmentsIdList as $segmentId) {
                           $flightDates = $dataListData->filterXPath('descendant-or-self::ns2:FlightSegment[@SegmentKey="' . $segmentId . '"]');
                           $depCode = $flightDates->filterXPath('descendant-or-self::ns2:Departure/ns2:AirportCode')->text();
                           $depDate = $flightDates->filterXPath('descendant-or-self::ns2:Departure/ns2:Date')->text();
                           $depTime = $flightDates->filterXPath('descendant-or-self::ns2:Departure/ns2:Time')->text();

                           $arrCode = $flightDates->filterXPath('descendant-or-self::ns2:Arrival/ns2:AirportCode')->text();
                           $arrDate = $flightDates->filterXPath('descendant-or-self::ns2:Arrival/ns2:Date')->text();
                           $arrTime = $flightDates->filterXPath('descendant-or-self::ns2:Arrival/ns2:Time')->text();
                           $classOfService = $flightDates->filterXPath('descendant-or-self::ns2:ClassOfService/ns2:MarketingName')->text();
                           $duration = $flightDates->filterXPath('descendant-or-self::ns2:FlightDetail/ns2:FlightDuration/ns2:Value')->text();

                           $flightData[] = [
                               'depCode' => $depCode,
                               'depDate' => $depDate,
                               'depTime' => $depTime,
                               'arrCode' => $arrCode,
                               'arrDate' => $arrDate,
                               'arrTime' => $arrTime,
                               'classOfService' => $classOfService,
                               'duration' => $duration
                           ];
                       }

               });

               $itemData = ['cost' => $cost, 'flightData' => $flightData];

               array_push($data, $itemData);
           });

       });

        return new JsonResponse($data, $response->getStatusCode());
    }

    public static function orderCreate(Client $client) {
        $response = $client->request('POST', 'http://devbagtag.us-east-1.elasticbeanstalk.com/bagtag/v1/issue',
            [
                'headers' => [
                    'userName' => self::$userName,
                    'password' => self::$password,
                    'Content-Type' => 'application/xml'
                ],
                'body' => <<<EOL
<soapenv:Envelope xmlns:soapenv = "http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns = "http://www.iata.org/IATA/EDIST/2017.2">
    <!--logFileName#D:\NDCAutomation\Scenario#_1435__RQRS_2019_Oct_04_06_16_15.log~-->
    <!--Service End-point : 10.196.23.45:6014-->
    <soapenv:Header/>
    <soapenv:Body>
        <ns:OrderRetrieveRQ Version = "17.2">
            <ns:PointOfSale>
                <ns:Location>
                    <ns:CountryCode>DE</ns:CountryCode>
                    <ns:CityCode>AYT</ns:CityCode>
                </ns:Location>
            </ns:PointOfSale>
            <ns:Document>
                <ns:Name>NDC</ns:Name>
                <ns:ReferenceVersion>17.2</ns:ReferenceVersion>
            </ns:Document>
            <ns:Party>
                <ns:Sender>
                    <ns:AgentUserSender>
                        <ns:Name>*****</ns:Name>
                        <ns:AgentUserID>*****</ns:AgentUserID>
                    </ns:AgentUserSender>
                </ns:Sender>
                <ns:Recipient>
                    <ns:ORA_Recipient>
                        <ns:AirlineID>XQ</ns:AirlineID>
                        <ns:Name>Sun Express</ns:Name>
                    </ns:ORA_Recipient>
                </ns:Recipient>
            </ns:Party>
            <ns:Query>
                <ns:Filters>
                    <ns:OrderID Owner = "XQ">N2DGQ8</ns:OrderID>
                </ns:Filters>
            </ns:Query>
        </ns:OrderRetrieveRQ>
    </soapenv:Body>
</soapenv:Envelope>
EOL
            ]
        );

    }


}