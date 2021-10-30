<?php

namespace ShoperPL\ShoperDistanceAPI\Model;

use ShoperPL\ShoperDistanceAPI\Config;
use ShoperPL\ShoperDistanceAPI\Database;

class Office
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

    /**
     * @var string latitude szerokość geograficzna
     */
    public $latitude;

    /**
     * @var string longitude długość geograficzna
     */
    public $longitude;

    public function __construct(string $city, string $street, string $latitude, string $longitude, int $id = null)
    {
        $this->id = $id;
        $this->city = $city;
        $this->street = $street;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}