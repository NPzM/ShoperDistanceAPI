<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Management;

use ShoperPL\ShoperDistanceAPI\Database;
use ShoperPL\ShoperDistanceAPI\Management\AbstractManagement;
use ShoperPL\ShoperDistanceAPI\Management\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Model\Spot;
use ShoperPL\ShoperDistanceAPI\HereApiClient;

class HereApi extends AbstractManagement
{
    public function __construct()
    {
        parent::__construct();

        $this->shoperDistanceApi = new ShoperDistanceApi;
        $this->client = new HereApiClient;
    }

    public function calculateDistance(int $id, object $parameters) {
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

        return $this->client->getDistance($office, $spot);
    }
}