<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Clients;

use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Model\Spot;

/**
* Klient do HereApi.
*/
class HereApiClient
{
    /**
     * @var string host HereApi
     */
    const HOST_NAME = 'https://router.hereapi.com/v8';

    /**
     * @var string typ transportu
     */
    const TRANSPORT_TYPE = 'car';

    /**
     * @var string wartość klucza używana do autoryzacji w HereApi
    */
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = $_ENV['HERE_API_KEY'];
    }

    /**
     * Metoda komunikuje się z HereApi i zwraca informacje na temat odległości i czasu podróży między dwoma punktami.
     *
     * @param Office $office biuro
     * @param Spot $spot punkt
     *
     * @return string response
     */
    public function getDistance(Office $office, Spot $spot): array
    {
        $curl = curl_init();
        $url = $this->generateURL($office->getCoordinates(),  $spot->getCoordinates());

        $defaults = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ];

        curl_setopt_array($curl, $defaults);
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new \Exception('Błąd podczas ostatniej operacji cURL', HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) !== HttpCodes::HTTP_OK) {
            throw new \Exception("Błąd HereAPI" . $response, HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->decodeResponse($response);
    }

    /**
     * Generuje url do HereApi.
     *
     * @param string $officeCoordinates współrzędne biura
     * @param string $spotCoordinates współrzędne punktu
     *
     * @return string url
     */
    private function generateURL(string $officeCoordinates, string $spotCoordinates): string
    {
        return sprintf('%s/routes?transportMode=%s&origin=%s&destination=%s&return=summary&apiKey=%s',
        self::HOST_NAME, self::TRANSPORT_TYPE, $officeCoordinates, $spotCoordinates, $this->apiKey);
    }

    /**
     * Rozszyfrowuje odpowiedź od HereApi na temat odgłości między punktami.
     *
     * @param string $response odpowiedź od HereApi
     *
     * @return array results rezultaty
     */
    private function decodeResponse(string $response): array
    {
        $summary = json_decode($response, true)['routes'][0]['sections'][0]['summary'];
        $length = $summary['length'];
        $duration = $summary['duration'];

        $results = [];
        if ($length > 1000) {
            $km  = floor($length / 1000);
            $results['length'] = sprintf('%s km', $km);
        } else {
            $results['length'] = sprintf('%s m', $length);
        }

        if ($duration >= 3600) {
            $hours = floor($duration / 3600);
            $minutes = floor(($duration / 60) % 60);
            $results['duration'] = sprintf('%s godzin i %s minut', $hours, $minutes);
        } else {
            $minutes = floor(($duration / 60) % 60);
            $results['duration'] = sprintf('%s minut', $minutes);
        }

        return $results;
    }
}
