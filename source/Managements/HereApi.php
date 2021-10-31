<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Managements;

use ShoperPL\ShoperDistanceAPI\Clients\HereApiClient;
use ShoperPL\ShoperDistanceAPI\Managements\AbstractManagement;
use ShoperPL\ShoperDistanceAPI\Managements\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Model\Spot;
use ShoperPL\ShoperDistanceAPI\Repository\Database;

class HereApi extends AbstractManagement
{
    public function __construct()
    {
        parent::__construct();

        $this->shoperDistanceApi = new ShoperDistanceApi;
        $this->client = new HereApiClient;
    }

    public function calculateDistance(int $id, object $parameters) {
        $this->logger->info('Rozpoczęcie obliczania odległości do firmy');

        //walidacja
        // $this->validateParameters(
        //     $parameters,
        //     [
        //         'destinationLatitude' => true,
        //         'destinationtLongitude' => true,
        //     ]
        // );

        $office = $this->shoperDistanceApi->getById($id);
        $spot = new Spot($parameters->latitude, $parameters->longitude);

        $informations = [];
        try {
            $informations['distance'] = $this->decodeResponse($this->client->getDistance($office, $spot));
        } catch (\Exception $exception) {
            $this->logger->error('Obliczanie odległości do firmy zakończyło się błędem');

            throw $exception;
        }

        $informations['office'] = ['City' => $office->city, 'Street' => $office->street];
        $informations['spot'] = $spot->getCoordinates();

        $this->logger->info('Obliczanie odległości do firmy zakończyło się sukcesem');

        return $informations;
    }

    private function decodeResponse($response) {
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