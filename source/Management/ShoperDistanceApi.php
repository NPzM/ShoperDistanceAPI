<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Management;

use ShoperPL\ShoperDistanceAPI\Management\AbstractManagement;
use ShoperPL\ShoperDistanceAPI\Model\Office;

class ShoperDistanceApi extends AbstractManagement
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Pobieranie wszystkich biur Shoper'a w bazie danych
     */
    public function getAll()
    {
        $this->logger->info('Pobieranie informacji na temat biur ShoperPL');

        $offices = [];
        foreach($this->database->all() as $result){
            $offices[] = new Office((int) $result['id'], $result['city'], $result['street'], $result['latitude'], $result['longitude']);
        }

        $this->logger->info('Pobranie informacji zakończyło się sukcesem');

        return $offices;
    }

    public function run()
    {
        Logger::enableSystemLogs();
    }
}