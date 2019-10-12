<?php

namespace Api;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class SunExpress_Api {

    public static $userName;
    public static $password = '12345';
    public static $ur;

    public static $cardNumber  = 'NxKj9GDLjCEO1ZV9Kd6ezl1PnhwFO6AnvPmBoRAbn48=%~~`%~~~~~~~%^**(%$#%am1dxNXNzPTDRXdd4D3vFEtNVxcZrFbIQkHcdXJjKxAMvJIUk+SUuR2mIdO4goIbCXn1Eek5H3KCE87lvqylJri1PEgGwyPE4DZ1iZuQaIPRwB94jEEDs+vaAuMDNt0hxQN2zdyFHe7VbR6BywjMajalWc5e32oY1V3Ad4xn2/YYAsRVcFOulyZP2HZpJVZHBXnRhp+tNjI4ivyeAGs9WmPL+eiyiS3gNHQeZ5FjlIuNdg1Wr9RnA1jUbEAmtTcShRnz2Tzo/ysgs0RRqVE9tN0HCXEBS9LxgRnwBFT1pvTQQQp6CLlPlCihQdx5Pfb0/ShXczenhDsd4j8lkMdCAA==';
    public static $seriesCode = 'oFSfEOeDdUDep3UC+SvRpw==%~~`%~~~~~~~%^**(%$#%Fw8/F/i7NC7bykWPhW9Uil4GiHXN62g1PTvKgdiUGe2GvWh2pOaMZCcM9bQX/XZfBmwyKfeiIdsu6qgmT0slguooa5X7q2HoMDq7Ox6kjawX744v9+rUhg4A/nmXe+4IboT5JEu2B4inoMPiLXJLpEOh/4oV3V2QBO32cic9az/5m09s5RNx2RB9bMdK4qNsuSLT1U8RaWr0QC76OD/c/8nO4oCxIe2E0ctiene6gONwbgVRyzOCyDV8/feRaoTJFzqZAodIz381GmMk+6at2UIPzn0b7AJ1Arix1/H2OVuSFRmBIceM28z0GPeq5oUsTd8pxCuVDFbUPdHwk6A4DA==';
    public static $cardHolderName = 'v3uNb9+FkDmirVsRgiLF5g==%~~`%~~~~~~~%^**(%$#%bDS+MHp5qKwtg80/Nf5JNrRXGyAuwH6SVAh9VJL4BR5qoq1h2URk2SVh2N8nLDBeV9FP/WReR+l4zzxzOlmcrXUeocDEMguce5rWXI1/msazqFmqxMlgseoPVm7iStFJ6h1bLJtk2gvHPAnDxlzQ9+UDERj0XQw/nTahLkMbxr52NwrkM3fjgRh/+2cNws2RW3Vsub3Fv9nRRADjLkDJfoLG+h7RDhbqV0CYQ9ZIkvCDo43Hs6bjmHdKD4MZoCtPo06qGx71h+CrZEGYe7/WVRE257A/V8+FhNmt0zPlmS3cNw8So3Kbzr4/HlInAx63ZIWni8aTkQmG3+ZOrP7FWw==';
    public static $expiration = '5c1NTE8bR1YLrMXWSSH0wA==%~~`%~~~~~~~%^**(%$#%Jn7/bfhgzMSH0tlv5AisOVzTen/K8NxOBY99w8hXWUu0kkGhs7zmpdnUECOd4VCNmv9FQWojQIoFLu3C3YKSxE5WZaIdy6UtarB1k20XyjdZjNwT4yiK+iQBzLqv9XRgMgRkrF78gN0IQvXrR8p8pcwKamJhqkyaG4L0ahAPoyKM1lTSVY/buu9gM9PW8tPudR8QqKt3Ow8imcekGiiPrpediq5RxYI44A3zPQf5CPFVwH70FjZ4dstd361m4dIPWCaEZLB0ZEDqgcoXK7d1oLrkV7/JSknR+10AK7PNl84OneiQZpN88WEUnYaxNJ8IWC2DiliJdRrnhdne2BTwjg==';

    public static function basicOneWaySearch(Client $client, $depDate, $arrDate) {

        $depDate = '2019-10-03';
        $arrDate = '2019-10-10';

        $depCode = 'FRA';
        $arrCode = 'SPU';

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
        <ns:AirShoppingRQ Version = "17.2">
            <ns:PointOfSale>
                <ns:Location>
                    <ns:CountryCode>DE</ns:CountryCode>
                    <ns:CityCode>FRA</ns:CityCode>
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
            <ns:CoreQuery>
                <ns:OriginDestinations>
                    <ns:OriginDestination>
                        <ns:Departure>
                            <ns:AirportCode>{$depCode}</ns:AirportCode>
                            <ns:Date>{$depDate}</ns:Date>
                        </ns:Departure>
                        <ns:Arrival>
                            <ns:AirportCode>{$arrCode}</ns:AirportCode>
                        </ns:Arrival>
                        <ns:CalendarDates DaysBefore = "0" DaysAfter = "0"/>
                    </ns:OriginDestination>
                    <ns:OriginDestination>
                        <ns:Departure>
                            <ns:AirportCode>{$arrCode}</ns:AirportCode>
                            <ns:Date>{$arrDate}</ns:Date>
                        </ns:Departure>
                        <ns:Arrival>
                            <ns:AirportCode>{$depCode}</ns:AirportCode>
                        </ns:Arrival>
                        <ns:CalendarDates DaysBefore = "0" DaysAfter = "0"/>
                    </ns:OriginDestination>
                </ns:OriginDestinations>
            </ns:CoreQuery>
            <ns:DataLists>
                <ns:PassengerList>
                    <ns:Passenger PassengerID = "V1_PAX.1">
                        <ns:PTC>ADT</ns:PTC>
                    </ns:Passenger>
                </ns:PassengerList>
            </ns:DataLists>
        </ns:AirShoppingRQ>
    </soapenv:Body>
</soapenv:Envelope>
EOL
            ]
        );

        $testResponse = <<<EOL
<soap:Envelope xmlns:soap = "http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <ns2:AirShoppingRS
            Version = "17.2"
            xmlns:ns2 = "http://www.iata.org/IATA/EDIST/2017.2"
            xmlns:ns3 = "http://www.ibsplc.com/iFlyRes/simpleTypes/2017.2">
            <ns2:Document>
                <ns2:Name>17.2</ns2:Name>
                <ns2:ReferenceVersion>17.2</ns2:ReferenceVersion>
            </ns2:Document>
            <ns2:Success/>
            <ns2:AirShoppingProcessing/>
            <ns2:OffersGroup>
                <ns2:AirlineOffers>
                    <ns2:Offer OfferID = "V1_OFFER.1569473970447" Owner = "XQ">
                        <ns2:ValidatingCarrier>XQ</ns2:ValidatingCarrier>
                        <ns2:OfferItem OfferItemID = "V1_OFFERITEM.1569473970438">
                            <ns2:TotalPriceDetail>
                                <ns2:TotalAmount>
                                    <ns2:DetailCurrencyPrice>
                                        <ns2:Total Code = "EUR">256.98</ns2:Total>
                                        <ns2:Details>
                                            <ns2:Detail>
                                                <ns2:SubTotal Code = "EUR">256.98</ns2:SubTotal>
                                                <ns2:Application>APPLIED FARE</ns2:Application>
                                            </ns2:Detail>
                                        </ns2:Details>
                                        <ns2:Taxes>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            <ns2:Breakdown>
                                                <ns2:Tax>
                                                    <ns2:Amount Code = "EUR">0.0</ns2:Amount>
                                                </ns2:Tax>
                                            </ns2:Breakdown>
                                        </ns2:Taxes>
                                        <ns2:Fees>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            <ns2:Breakdown>
                                                <ns2:Fee>
                                                    <ns2:Amount Code = "EUR">0.0</ns2:Amount>
                                                </ns2:Fee>
                                            </ns2:Breakdown>
                                        </ns2:Fees>
                                    </ns2:DetailCurrencyPrice>
                                </ns2:TotalAmount>
                                <ns2:BaseAmount Code = "EUR">192.16</ns2:BaseAmount>
                                <ns2:Surcharges>
                                    <ns2:Surcharge>
                                        <ns2:Total Code = "EUR">0.0</ns2:Total>
                                    </ns2:Surcharge>
                                </ns2:Surcharges>
                                <ns2:Taxes>
                                    <ns2:Total Code = "EUR">0.0</ns2:Total>
                                </ns2:Taxes>
                            </ns2:TotalPriceDetail>
                            <ns2:Service ServiceID = "V1_SRVC.1569473970457">
                                <ns2:PassengerRefs>V1_PAX.1</ns2:PassengerRefs>
                                <ns2:FlightRefs>V1_FL.1569473970446 V1_FL.1569473970434</ns2:FlightRefs>
                            </ns2:Service>
                            <ns2:FareDetail>
                                <ns2:PassengerRefs>V1_PAX.1</ns2:PassengerRefs>
                                <ns2:Price>
                                    <ns2:TotalAmount>
                                        <ns2:DetailCurrencyPrice>
                                            <ns2:Total Code = "EUR">256.98</ns2:Total>
                                            <ns2:Details>
                                                <ns2:Detail>
                                                    <ns2:SubTotal Code = "EUR">256.98</ns2:SubTotal>
                                                    <ns2:Application>APPLIED FARE</ns2:Application>
                                                </ns2:Detail>
                                            </ns2:Details>
                                        </ns2:DetailCurrencyPrice>
                                    </ns2:TotalAmount>
                                    <ns2:BaseAmount Code = "EUR">192.16</ns2:BaseAmount>
                                    <ns2:Surcharges>
                                        <ns2:Surcharge>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                        </ns2:Surcharge>
                                    </ns2:Surcharges>
                                    <ns2:Taxes>
                                        <ns2:Total Code = "EUR">0.0</ns2:Total>
                                    </ns2:Taxes>
                                </ns2:Price>
                                <ns2:FareComponent>
                                    <ns2:Price>
                                        <ns2:TotalAmount>
                                            <ns2:DetailCurrencyPrice>
                                                <ns2:Total Code = "EUR">87.99</ns2:Total>
                                                <ns2:Details>
                                                    <ns2:Detail>
                                                        <ns2:SubTotal Code = "EUR">87.99</ns2:SubTotal>
                                                        <ns2:Application>APPLIED FARE</ns2:Application>
                                                    </ns2:Detail>
                                                </ns2:Details>
                                            </ns2:DetailCurrencyPrice>
                                        </ns2:TotalAmount>
                                        <ns2:BaseAmount Code = "EUR">39.67</ns2:BaseAmount>
                                        <ns2:Surcharges>
                                            <ns2:Surcharge>
                                                <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            </ns2:Surcharge>
                                        </ns2:Surcharges>
                                        <ns2:Taxes>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                        </ns2:Taxes>
                                    </ns2:Price>
                                    <ns2:FareBasis>
                                        <ns2:FareBasisCode refs = "V1_FMD.1569473970432 V1_FARECOMPREFS.1569473970435">
                                            <ns2:Code>DINT</ns2:Code>
                                        </ns2:FareBasisCode>
                                        <ns2:CabinType>
                                            <ns2:CabinTypeCode
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">Y</ns2:CabinTypeCode>
                                            <ns2:CabinTypeName
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">ECONOMY</ns2:CabinTypeName>
                                        </ns2:CabinType>
                                    </ns2:FareBasis>
                                    <ns2:FareRules>
                                        <ns2:Ticketing>
                                            <ns2:TicketlessInd>false</ns2:TicketlessInd>
                                        </ns2:Ticketing>
                                    </ns2:FareRules>
                                    <ns2:SegmentRefs>V1_SEG.1569473970433</ns2:SegmentRefs>
                                </ns2:FareComponent>
                                <ns2:FareComponent>
                                    <ns2:Price>
                                        <ns2:TotalAmount>
                                            <ns2:DetailCurrencyPrice>
                                                <ns2:Total Code = "EUR">168.99</ns2:Total>
                                                <ns2:Details>
                                                    <ns2:Detail>
                                                        <ns2:SubTotal Code = "EUR">168.99</ns2:SubTotal>
                                                        <ns2:Application>APPLIED FARE</ns2:Application>
                                                    </ns2:Detail>
                                                </ns2:Details>
                                            </ns2:DetailCurrencyPrice>
                                        </ns2:TotalAmount>
                                        <ns2:BaseAmount Code = "EUR">152.49</ns2:BaseAmount>
                                        <ns2:Surcharges>
                                            <ns2:Surcharge>
                                                <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            </ns2:Surcharge>
                                        </ns2:Surcharges>
                                        <ns2:Taxes>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                        </ns2:Taxes>
                                    </ns2:Price>
                                    <ns2:FareBasis>
                                        <ns2:FareBasisCode refs = "V1_FMD.1569473970436 V1_FARECOMPREFS.1569473970439">
                                            <ns2:Code>XINT</ns2:Code>
                                        </ns2:FareBasisCode>
                                        <ns2:CabinType>
                                            <ns2:CabinTypeCode
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">Y</ns2:CabinTypeCode>
                                            <ns2:CabinTypeName
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">ECONOMY</ns2:CabinTypeName>
                                        </ns2:CabinType>
                                    </ns2:FareBasis>
                                    <ns2:FareRules>
                                        <ns2:Ticketing>
                                            <ns2:TicketlessInd>false</ns2:TicketlessInd>
                                        </ns2:Ticketing>
                                    </ns2:FareRules>
                                    <ns2:SegmentRefs>V1_SEG.1569473970437</ns2:SegmentRefs>
                                </ns2:FareComponent>
                            </ns2:FareDetail>
                        </ns2:OfferItem>
                    </ns2:Offer>
                    <ns2:Offer OfferID = "V1_OFFER.1569473970456" Owner = "XQ">
                        <ns2:ValidatingCarrier>XQ</ns2:ValidatingCarrier>
                        <ns2:OfferItem OfferItemID = "V1_OFFERITEM.1569473970461">
                            <ns2:TotalPriceDetail>
                                <ns2:TotalAmount>
                                    <ns2:DetailCurrencyPrice>
                                        <ns2:Total Code = "EUR">256.98</ns2:Total>
                                        <ns2:Details>
                                            <ns2:Detail>
                                                <ns2:SubTotal Code = "EUR">256.98</ns2:SubTotal>
                                                <ns2:Application>APPLIED FARE</ns2:Application>
                                            </ns2:Detail>
                                        </ns2:Details>
                                        <ns2:Taxes>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            <ns2:Breakdown>
                                                <ns2:Tax>
                                                    <ns2:Amount Code = "EUR">0.0</ns2:Amount>
                                                </ns2:Tax>
                                            </ns2:Breakdown>
                                        </ns2:Taxes>
                                        <ns2:Fees>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            <ns2:Breakdown>
                                                <ns2:Fee>
                                                    <ns2:Amount Code = "EUR">0.0</ns2:Amount>
                                                </ns2:Fee>
                                            </ns2:Breakdown>
                                        </ns2:Fees>
                                    </ns2:DetailCurrencyPrice>
                                </ns2:TotalAmount>
                                <ns2:BaseAmount Code = "EUR">192.16</ns2:BaseAmount>
                                <ns2:Surcharges>
                                    <ns2:Surcharge>
                                        <ns2:Total Code = "EUR">0.0</ns2:Total>
                                    </ns2:Surcharge>
                                </ns2:Surcharges>
                                <ns2:Taxes>
                                    <ns2:Total Code = "EUR">0.0</ns2:Total>
                                </ns2:Taxes>
                            </ns2:TotalPriceDetail>
                            <ns2:Service ServiceID = "V1_SRVC.1569473970460">
                                <ns2:PassengerRefs>V1_PAX.1</ns2:PassengerRefs>
                                <ns2:FlightRefs>V1_FL.1569473970459 V1_FL.1569473970434</ns2:FlightRefs>
                            </ns2:Service>
                            <ns2:FareDetail>
                                <ns2:PassengerRefs>V1_PAX.1</ns2:PassengerRefs>
                                <ns2:Price>
                                    <ns2:TotalAmount>
                                        <ns2:DetailCurrencyPrice>
                                            <ns2:Total Code = "EUR">256.98</ns2:Total>
                                            <ns2:Details>
                                                <ns2:Detail>
                                                    <ns2:SubTotal Code = "EUR">256.98</ns2:SubTotal>
                                                    <ns2:Application>APPLIED FARE</ns2:Application>
                                                </ns2:Detail>
                                            </ns2:Details>
                                        </ns2:DetailCurrencyPrice>
                                    </ns2:TotalAmount>
                                    <ns2:BaseAmount Code = "EUR">192.16</ns2:BaseAmount>
                                    <ns2:Surcharges>
                                        <ns2:Surcharge>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                        </ns2:Surcharge>
                                    </ns2:Surcharges>
                                    <ns2:Taxes>
                                        <ns2:Total Code = "EUR">0.0</ns2:Total>
                                    </ns2:Taxes>
                                </ns2:Price>
                                <ns2:FareComponent>
                                    <ns2:Price>
                                        <ns2:TotalAmount>
                                            <ns2:DetailCurrencyPrice>
                                                <ns2:Total Code = "EUR">87.99</ns2:Total>
                                                <ns2:Details>
                                                    <ns2:Detail>
                                                        <ns2:SubTotal Code = "EUR">87.99</ns2:SubTotal>
                                                        <ns2:Application>APPLIED FARE</ns2:Application>
                                                    </ns2:Detail>
                                                </ns2:Details>
                                            </ns2:DetailCurrencyPrice>
                                        </ns2:TotalAmount>
                                        <ns2:BaseAmount Code = "EUR">39.67</ns2:BaseAmount>
                                        <ns2:Surcharges>
                                            <ns2:Surcharge>
                                                <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            </ns2:Surcharge>
                                        </ns2:Surcharges>
                                        <ns2:Taxes>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                        </ns2:Taxes>
                                    </ns2:Price>
                                    <ns2:FareBasis>
                                        <ns2:FareBasisCode refs = "V1_FMD.1569473970432 V1_FARECOMPREFS.1569473970435">
                                            <ns2:Code>DINT</ns2:Code>
                                        </ns2:FareBasisCode>
                                        <ns2:CabinType>
                                            <ns2:CabinTypeCode
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">Y</ns2:CabinTypeCode>
                                            <ns2:CabinTypeName
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">ECONOMY</ns2:CabinTypeName>
                                        </ns2:CabinType>
                                    </ns2:FareBasis>
                                    <ns2:FareRules>
                                        <ns2:Ticketing>
                                            <ns2:TicketlessInd>false</ns2:TicketlessInd>
                                        </ns2:Ticketing>
                                    </ns2:FareRules>
                                    <ns2:SegmentRefs>V1_SEG.1569473970458</ns2:SegmentRefs>
                                </ns2:FareComponent>
                                <ns2:FareComponent>
                                    <ns2:Price>
                                        <ns2:TotalAmount>
                                            <ns2:DetailCurrencyPrice>
                                                <ns2:Total Code = "EUR">168.99</ns2:Total>
                                                <ns2:Details>
                                                    <ns2:Detail>
                                                        <ns2:SubTotal Code = "EUR">168.99</ns2:SubTotal>
                                                        <ns2:Application>APPLIED FARE</ns2:Application>
                                                    </ns2:Detail>
                                                </ns2:Details>
                                            </ns2:DetailCurrencyPrice>
                                        </ns2:TotalAmount>
                                        <ns2:BaseAmount Code = "EUR">152.49</ns2:BaseAmount>
                                        <ns2:Surcharges>
                                            <ns2:Surcharge>
                                                <ns2:Total Code = "EUR">0.0</ns2:Total>
                                            </ns2:Surcharge>
                                        </ns2:Surcharges>
                                        <ns2:Taxes>
                                            <ns2:Total Code = "EUR">0.0</ns2:Total>
                                        </ns2:Taxes>
                                    </ns2:Price>
                                    <ns2:FareBasis>
                                        <ns2:FareBasisCode refs = "V1_FMD.1569473970436 V1_FARECOMPREFS.1569473970439">
                                            <ns2:Code>XINT</ns2:Code>
                                        </ns2:FareBasisCode>
                                        <ns2:CabinType>
                                            <ns2:CabinTypeCode
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">Y</ns2:CabinTypeCode>
                                            <ns2:CabinTypeName
                                                xsi:type = "xs:string"
                                                xmlns:xsi = "http://www.w3.org/2001/XMLSchema-instance"
                                                xmlns:xs = "http://www.w3.org/2001/XMLSchema">ECONOMY</ns2:CabinTypeName>
                                        </ns2:CabinType>
                                    </ns2:FareBasis>
                                    <ns2:FareRules>
                                        <ns2:Ticketing>
                                            <ns2:TicketlessInd>false</ns2:TicketlessInd>
                                        </ns2:Ticketing>
                                    </ns2:FareRules>
                                    <ns2:SegmentRefs>V1_SEG.1569473970437</ns2:SegmentRefs>
                                </ns2:FareComponent>
                            </ns2:FareDetail>
                        </ns2:OfferItem>
                    </ns2:Offer>
                </ns2:AirlineOffers>
            </ns2:OffersGroup>
            <ns2:DataLists>
                <ns2:PassengerList>
                    <ns2:Passenger PassengerID = "V1_PAX.1">
                        <ns2:PTC>ADT</ns2:PTC>
                    </ns2:Passenger>
                </ns2:PassengerList>
                <ns2:FlightSegmentList>
                    <ns2:FlightSegment SegmentKey = "V1_SEG.1569473970433" ElectronicTicketInd = "true">
                        <ns2:Departure>
                            <ns2:AirportCode>FRA</ns2:AirportCode>
                            <ns2:Date>2019-11-10Z</ns2:Date>
                            <ns2:Time>13:45</ns2:Time>
                        </ns2:Departure>
                        <ns2:Arrival>
                            <ns2:AirportCode>AYT</ns2:AirportCode>
                            <ns2:Date>2019-11-10Z</ns2:Date>
                            <ns2:Time>19:10</ns2:Time>
                            <ns2:ChangeOfDay>0</ns2:ChangeOfDay>
                        </ns2:Arrival>
                        <ns2:MarketingCarrier>
                            <ns2:AirlineID>XQ</ns2:AirlineID>
                            <ns2:FlightNumber>141</ns2:FlightNumber>
                        </ns2:MarketingCarrier>
                        <ns2:Equipment>
                            <ns2:AircraftCode>73H</ns2:AircraftCode>
                            <ns2:AirlineEquipCode>800</ns2:AirlineEquipCode>
                        </ns2:Equipment>
                        <ns2:ClassOfService>
                            <ns2:Code SeatsLeft = "9">D</ns2:Code>
                            <ns2:MarketingName CabinDesignator = "Y">ECONOMY</ns2:MarketingName>
                        </ns2:ClassOfService>
                        <ns2:FlightDetail>
                            <ns2:FlightDuration>
                                <ns2:Value>PT3H25M0.000S</ns2:Value>
                            </ns2:FlightDuration>
                        </ns2:FlightDetail>
                    </ns2:FlightSegment>
                    <ns2:FlightSegment SegmentKey = "V1_SEG.1569473970437" ElectronicTicketInd = "true">
                        <ns2:Departure>
                            <ns2:AirportCode>AYT</ns2:AirportCode>
                            <ns2:Date>2019-11-12Z</ns2:Date>
                            <ns2:Time>11:00</ns2:Time>
                        </ns2:Departure>
                        <ns2:Arrival>
                            <ns2:AirportCode>FRA</ns2:AirportCode>
                            <ns2:Date>2019-11-12Z</ns2:Date>
                            <ns2:Time>12:50</ns2:Time>
                            <ns2:ChangeOfDay>0</ns2:ChangeOfDay>
                        </ns2:Arrival>
                        <ns2:MarketingCarrier>
                            <ns2:AirlineID>XQ</ns2:AirlineID>
                            <ns2:FlightNumber>140</ns2:FlightNumber>
                        </ns2:MarketingCarrier>
                        <ns2:Equipment>
                            <ns2:AircraftCode>73H</ns2:AircraftCode>
                            <ns2:AirlineEquipCode>800</ns2:AirlineEquipCode>
                        </ns2:Equipment>
                        <ns2:ClassOfService>
                            <ns2:Code SeatsLeft = "9">X</ns2:Code>
                            <ns2:MarketingName CabinDesignator = "Y">ECONOMY</ns2:MarketingName>
                        </ns2:ClassOfService>
                        <ns2:FlightDetail>
                            <ns2:FlightDuration>
                                <ns2:Value>PT3H50M0.000S</ns2:Value>
                            </ns2:FlightDuration>
                        </ns2:FlightDetail>
                    </ns2:FlightSegment>
                    <ns2:FlightSegment SegmentKey = "V1_SEG.1569473970458" ElectronicTicketInd = "true">
                        <ns2:Departure>
                            <ns2:AirportCode>FRA</ns2:AirportCode>
                            <ns2:Date>2019-11-10Z</ns2:Date>
                            <ns2:Time>17:40</ns2:Time>
                        </ns2:Departure>
                        <ns2:Arrival>
                            <ns2:AirportCode>AYT</ns2:AirportCode>
                            <ns2:Date>2019-11-10Z</ns2:Date>
                            <ns2:Time>23:05</ns2:Time>
                            <ns2:ChangeOfDay>0</ns2:ChangeOfDay>
                        </ns2:Arrival>
                        <ns2:MarketingCarrier>
                            <ns2:AirlineID>XQ</ns2:AirlineID>
                            <ns2:FlightNumber>145</ns2:FlightNumber>
                        </ns2:MarketingCarrier>
                        <ns2:Equipment>
                            <ns2:AircraftCode>73H</ns2:AircraftCode>
                            <ns2:AirlineEquipCode>800</ns2:AirlineEquipCode>
                        </ns2:Equipment>
                        <ns2:ClassOfService>
                            <ns2:Code SeatsLeft = "9">D</ns2:Code>
                            <ns2:MarketingName CabinDesignator = "Y">ECONOMY</ns2:MarketingName>
                        </ns2:ClassOfService>
                        <ns2:FlightDetail>
                            <ns2:FlightDuration>
                                <ns2:Value>PT3H25M0.000S</ns2:Value>
                            </ns2:FlightDuration>
                        </ns2:FlightDetail>
                    </ns2:FlightSegment>
                </ns2:FlightSegmentList>
                <ns2:FlightList>
                    <ns2:Flight FlightKey = "V1_FL.1569473970446">
                        <ns2:Journey>
                            <ns2:Time>P0Y0M0DT3H25M0.000S</ns2:Time>
                        </ns2:Journey>
                        <ns2:SegmentReferences>V1_SEG.1569473970433</ns2:SegmentReferences>
                    </ns2:Flight>
                    <ns2:Flight FlightKey = "V1_FL.1569473970434">
                        <ns2:Journey>
                            <ns2:Time>P0Y0M0DT3H50M0.000S</ns2:Time>
                        </ns2:Journey>
                        <ns2:SegmentReferences>V1_SEG.1569473970437</ns2:SegmentReferences>
                    </ns2:Flight>
                    <ns2:Flight FlightKey = "V1_FL.1569473970459">
                        <ns2:Journey>
                            <ns2:Time>P0Y0M0DT3H25M0.000S</ns2:Time>
                        </ns2:Journey>
                        <ns2:SegmentReferences>V1_SEG.1569473970458</ns2:SegmentReferences>
                    </ns2:Flight>
                </ns2:FlightList>
                <ns2:OriginDestinationList>
                    <ns2:OriginDestination OriginDestinationKey = "V1_OD.1569473970463">
                        <ns2:DepartureCode>FRA</ns2:DepartureCode>
                        <ns2:ArrivalCode>AYT</ns2:ArrivalCode>
                        <ns2:FlightReferences OnPoint = "FRA" OffPoint = "AYT">V1_FL.1569473970459 V1_FL.1569473970446</ns2:FlightReferences>
                    </ns2:OriginDestination>
                    <ns2:OriginDestination OriginDestinationKey = "V1_OD.1569473970462">
                        <ns2:DepartureCode>AYT</ns2:DepartureCode>
                        <ns2:ArrivalCode>FRA</ns2:ArrivalCode>
                        <ns2:FlightReferences OnPoint = "AYT" OffPoint = "FRA">V1_FL.1569473970434</ns2:FlightReferences>
                    </ns2:OriginDestination>
                </ns2:OriginDestinationList>
            </ns2:DataLists>
            <ns2:Metadata>
                <ns2:Shopping>
                    <ns2:ShopMetadataGroup>
                        <ns2:Offer>
                            <ns2:OfferMetadatas>
                                <ns2:OfferMetadata MetadataKey = "V1_MDK.1569473970444">
                                    <ns2:AugmentationPoint>
                                        <ns2:AugPoint Key = "V1_FMD.1569473970432">
                                            <ns3:FareDetailAugPoint>
                                                <FareType>SUNECO</FareType>
                                                <FareLevel>ST</FareLevel>
                                                <FareId>318635</FareId>
                                            </ns3:FareDetailAugPoint>
                                        </ns2:AugPoint>
                                        <ns2:AugPoint Key = "V1_FMD.1569473970436">
                                            <ns3:FareDetailAugPoint>
                                                <FareType>SUNECO</FareType>
                                                <FareLevel>ST</FareLevel>
                                                <FareId>318247</FareId>
                                            </ns3:FareDetailAugPoint>
                                        </ns2:AugPoint>
                                        <ns2:AugPoint Key = "V1_FARECOMPREFS.1569473970435">
                                            <ns3:FareComponentAugPoint>
                                                <BaseFare Code = "EUR">29.67</BaseFare>
                                                <DisplayFare Code = "EUR">87.99</DisplayFare>
                                                <Discount Code = "EUR">0.0</Discount>
                                            </ns3:FareComponentAugPoint>
                                        </ns2:AugPoint>
                                        <ns2:AugPoint Key = "V1_FARECOMPREFS.1569473970439">
                                            <ns3:FareComponentAugPoint>
                                                <BaseFare Code = "EUR">142.49</BaseFare>
                                                <DisplayFare Code = "EUR">168.99</DisplayFare>
                                                <Discount Code = "EUR">0.0</Discount>
                                            </ns3:FareComponentAugPoint>
                                        </ns2:AugPoint>
                                    </ns2:AugmentationPoint>
                                </ns2:OfferMetadata>
                            </ns2:OfferMetadatas>
                        </ns2:Offer>
                    </ns2:ShopMetadataGroup>
                </ns2:Shopping>
                <ns2:Other>
                    <ns2:OtherMetadata>
                        <ns2:CurrencyMetadatas>
                            <ns2:CurrencyMetadata MetadataKey = "EUR">
                                <ns2:Decimals>2</ns2:Decimals>
                            </ns2:CurrencyMetadata>
                        </ns2:CurrencyMetadatas>
                    </ns2:OtherMetadata>
                    <ns2:OtherMetadata>
                        <ns2:PriceMetadatas>
                            <ns2:PriceMetadata MetadataKey = "V1_MDK.1569473970445">
                                <ns2:AugmentationPoint/>
                            </ns2:PriceMetadata>
                        </ns2:PriceMetadatas>
                    </ns2:OtherMetadata>
                </ns2:Other>
            </ns2:Metadata>
        </ns2:AirShoppingRS>
    </soap:Body>
</soap:Envelope>

EOL;

/*        $crawler = new Crawler($testResponse);

        foreach ($crawler as $domElement) {
            var_dump($domElement->nodeName);
        }

        $crawler = $crawler->filter('body > p');
        $crawler = $crawler->filterXPath('descendant-or-self::body/p');*/

        return $testResponse;

        return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode());
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