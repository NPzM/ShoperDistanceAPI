<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Constants;

/**
 * Klasa definiująca stałe wymaganych parametrów.
 */
class RequiredParameters
{
    /**
     * Zestawy wymaganych parametrów dla modeli.
     */
    const OFFICE = ['street', 'city', 'latitude', 'longitude'];
    const SPOT = ['latitude', 'longitude'];

    /**
     * Stałe wymaganych parametrów.
     */
    const REQUIRED_CITY = 'city';
    const REQUIRED_LATITUDE = 'latitude';
    const REQUIRED_LONGITUDE = 'longitude';
    const REQUIRED_STREET = 'street';
}
