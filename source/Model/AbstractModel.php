<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Model;

abstract class AbstractModel
{
    /**
     * @var string latitude szerokość geograficzna
     */
    public $latitude;

    /**
     * @var string longitude długość geograficzna
     */
    public $longitude;

    public function getCoordinates(): string
    {
        return "{$this->latitude},{$this->longitude}";
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
}
