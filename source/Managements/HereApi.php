<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Managements;

use ShoperPL\ShoperDistanceAPI\Clients\HereApiClient;
use ShoperPL\ShoperDistanceAPI\Constants\RequiredParameters;
use ShoperPL\ShoperDistanceAPI\Managements\AbstractManagement;
use ShoperPL\ShoperDistanceAPI\Managements\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Model\Spot;
use ShoperPL\ShoperDistanceAPI\Repository\Database;
use ShoperPL\ShoperDistanceAPI\Traits\ValidatorTrait;

/**
 * Klasa do zarządania HereAPI.
 */
class HereApi extends AbstractManagement
{
    use ValidatorTrait;

    public function __construct()
    {
        parent::__construct();

        $this->shoperDistanceApi = new ShoperDistanceApi;
        $this->client = new HereApiClient;
    }

    /**
     * Metoda zwraca dystans i czas podróży pomiędzy punktami oraz dodatkowe informacje na temat szczegółów tych punktów.
     *
     * @param int $id indentyfikator biura
     * @param object $parameters parametry punktu
     *
     * @return array results rezultaty
     */
    public function calculateDistance(int $id, object $parameters): array
    {
        $this->logger->info('Rozpoczęcie obliczania odległości do firmy');

        $informations = [];
        try {
            $this->checkRequiredParameters($parameters, RequiredParameters::SPOT);
            $this->validateParameters($parameters);

            $office = $this->shoperDistanceApi->getById($id);
            $spot = new Spot($parameters->latitude, $parameters->longitude);
            $informations['distance'] = $this->decodeResponse($this->client->getDistance($office, $spot));
        } catch (\Exception $exception) {
            $this->logger->error('Obliczanie odległości do firmy zakończyło się błędem');

            throw $exception;
        }

        $informations['office'] = ['City' => $office->getCity(), 'Street' => $office->getStreet()];
        $informations['spot'] = $spot->getCoordinates();

        $this->logger->info('Obliczanie odległości do firmy zakończyło się sukcesem');

        return $informations;
    }

    /**
     * Dekodowuje odpowiedź od HereApi na temat odgłości między punktami.
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