<?php

namespace ShoperPL\ShoperDistanceAPI\Clients;

use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Model\Spot;
use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;

class HereApiClient
{
    const HOST_NAME = 'https://router.hereapi.com/v8';

    const TRANSPORT_TYPE = 'car';
    const DISTANCE_UNIT = 'meters';

    /**
     *  @var string wartość klucza używana do autoryzacji przez api
    */
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = 'PbSPQq4YDif8MxnuWlBh0CdVoooypjfiETGwwkEAcZk';
    }

    /**
    * Metoda komunikuje się z HereApi i zwraca informacje na temat odległości i czasu podróży między dwoma punktami.
    */
    public function getDistance(Office $office, Spot $spot)
    {
        $curl = curl_init();
        $url = $this->generateURL($office->getCoordinates(),  $spot->getCoordinates());

        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        );

        curl_setopt_array($curl, $defaults);
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \Exception('Błąd podczas ostatniej operacji cURL', HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        } elseif (curl_getinfo($curl, CURLINFO_HTTP_CODE !== 200)) {
            throw new \Exception("Błąd HereAPI" . "<br>" . $response, HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    private function generateURL(string $officeCoordinates, string $spotCoordinates) {
        return sprintf('%s/routes?transportMode=%s&origin=%s&destination=%s&return=summary&apiKey=%s',
        self::HOST_NAME, self::TRANSPORT_TYPE, $officeCoordinates, $spotCoordinates, $this->apiKey);
    }
}
