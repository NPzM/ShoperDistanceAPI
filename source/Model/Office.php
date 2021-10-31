<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Model;

use ShoperPL\ShoperDistanceAPI\Config;
use ShoperPL\ShoperDistanceAPI\Model\AbstractModel;
use ShoperPL\ShoperDistanceAPI\Repository\Database;

class Office extends AbstractModel
{
    /**
     * @var string city miasto
     */
    public $city;
    /**
     * @var int id indentyfikator
     */
    public $id;

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