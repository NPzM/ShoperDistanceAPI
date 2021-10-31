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