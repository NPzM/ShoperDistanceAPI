<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Model;

use ShoperPL\ShoperDistanceAPI\Model\AbstractModel;

/**
* Klasa punktu, od którego będziemy odległość od firmy
*/
class Spot extends AbstractModel
{
    public function __construct(string $latitude, string $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}