<?php

namespace ShoperPL\ShoperDistanceAPI;

use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Model\Spot;
use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;

class HereApiClient
{
    const TRANSPORT_TYPE = 'car';
    const DISTANCE_UNIT = 'meters';

    /** @var string wartość klucza używana do autoryzacji przez api */
    private $apiKey;

    /** @var string nazwa hosta pod którym znajduje się API */
    private $hostname;

    public function __construct()
    {
        $this->hostname = 'https://router.hereapi.com/v8/';
        $this->apiKey = 'PbSPQq4YDif8MxnuWlBh0CdVoooypjfiETGwwkEAcZk';
    }

    /**
    * Metoda zwaraca odległość między dwoma punktami
    */
    public function getDistance(Office $office, Spot $spot)
    {
        $curl = curl_init();
        $url = sprintf('https://router.hereapi.com/v8/routes?transportMode=%s&origin=%s&destination=%s&return=summary&apiKey=%s',
            self::TRANSPORT_TYPE, $office->getCoordinates(), $spot->getCoordinates(), $this->apiKey);

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
            throw new \Exception('Curl error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
        }

        var_dump($response);

        switch ($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
            case 200:
                return $response;
              break;
            case 400:
                throw new \Exception('HereApi validation  error', ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
            default:
                throw new \Exception('Unexpected HereApi HTTP code response: ' . $http_code, ApiConstants::HTTP_INTERNAL_SERVER_ERROR);
          }
    }
}
