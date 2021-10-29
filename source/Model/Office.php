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

    public function __construct(int $id, string $city, string $street, string $latitude, string $longitude)
    {
        $this->id = $id;
        $this->city = $city;
        $this->street = $street;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function all(): ?object
    {
        return $this->database->all();
    }

    public static function add()
    {

    }

    public static function delete()
    {
        return self::$offices;
    }

    public static function update()
    {
        return self::$offices;
    }
}