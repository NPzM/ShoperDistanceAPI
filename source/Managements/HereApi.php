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

    //Opcjonalne parametry na potrzeby testów jednoskowych
    public function __construct($client = null, $shoperDistanceApi = null)
    {
        parent::__construct();

        if (is_null($shoperDistanceApi)) {
            $this->shoperDistanceApi = new ShoperDistanceApi;
        } else {
            $this->shoperDistanceApi = $shoperDistanceApi;
        }

        if (is_null($client)) {
            $this->client = new HereApiClient;
        } else {
            $this->client = $client;
        }
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
            $informations['distance'] = $this->client->getDistance($office, $spot);
        } catch (\Exception $exception) {
            $this->logger->error('Obliczanie odległości do firmy zakończyło się błędem');

            throw $exception;
        }

        $informations['office'] = ['city' => $office->getCity(), 'street' => $office->getStreet()];
        $informations['spot'] = $spot->getCoordinates();

        $this->logger->info('Obliczanie odległości do firmy zakończyło się sukcesem');

        return $informations;
    }
}