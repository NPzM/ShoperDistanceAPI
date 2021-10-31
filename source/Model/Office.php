<?php

namespace ShoperPL\ShoperDistanceAPI\Model;

use ShoperPL\ShoperDistanceAPI\Config;
use ShoperPL\ShoperDistanceAPI\Repository\Database;
use ShoperPL\ShoperDistanceAPI\Model\AbstractModel;

class Office extends AbstractModel
{
    /**
     * @var int id indentyfikator
     */
    public $id;

    /**
     * @var string city miasto
     */
    public $city;

    /**
     * @var int street ulica
     */
    public $street;

    public function __construct(string $city, string $street, string $latitude, string $longitude, int $id = null)
    {
        $this->id = $id;
        $this->city = $city;
        $this->street = $street;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}