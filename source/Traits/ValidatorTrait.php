<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Traits;

use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;
use ShoperPL\ShoperDistanceAPI\Constants\RegularExpression;
use ShoperPL\ShoperDistanceAPI\Constants\RequiredParameters;

trait ValidatorTrait
{
    /**
     * Walidacja przychodzących parametrów.
     *
     * @param object $parameters parametry
     *
     * @return bool wyniki walidacji
     */
    public function checkRequiredParameters(object $parameters, array $model): bool
    {
        if (!isset($parameters->city) && in_array(RequiredParameters::REQUIRED_CITY, $model, true)) {
            $this->logger->error('Brak parametru city w formularzu!');

            throw new \Exception('Brak parametru city w formularzu!', HttpCodes::HTTP_BAD_REQUEST);
        }

        if (!isset($parameters->street) && in_array(RequiredParameters::REQUIRED_STREET, $model, true)) {
            $this->logger->error('Brak parametru street w formularzu!');

            throw new \Exception('Brak parametru street w formularzu!', HttpCodes::HTTP_BAD_REQUEST);
        }

        if (!isset($parameters->latitude) && in_array(RequiredParameters::REQUIRED_LATITUDE, $model, true)) {
            $this->logger->error('Brak parametru latitude w formularzu!');

            throw new \Exception('Brak parametru latitude w formularzu!', HttpCodes::HTTP_BAD_REQUEST);
        }

        if (!isset($parameters->longitude) && in_array(RequiredParameters::REQUIRED_LONGITUDE, $model, true)) {
            $this->logger->error('Brak parametru longitude w formularzu!');

            throw new \Exception('Brak parametru longitude w formularzu!', HttpCodes::HTTP_BAD_REQUEST);
        }

        return true;
    }

    /**
     * Walidacja przychodzących parametrów.
     *
     * @param object $parameters parametry
     *
     * @return bool wyniki walidacji
     */
    public function validateParameters(object $parameters): bool
    {
        if (isset($parameters->city) && !preg_match(RegularExpression::REGEX_CITY, $parameters->city)) {
            $this->logger->error('Niepomyślna walidacja parametru city');

            throw new \Exception('Parametr city może mieć tylko litery!', HttpCodes::HTTP_BAD_REQUEST);
        }

        if (isset($parameters->street) && !preg_match(RegularExpression::REGEX_STREET, $parameters->street)) {
            $this->logger->error('Niepomyślna walidacja parametru street');

            throw new \Exception('Parametr street może mieć tylko litery i cyfry!', HttpCodes::HTTP_BAD_REQUEST);
        }

        if (isset($parameters->latitude) && !preg_match(RegularExpression::REGEX_COORDINATES, $parameters->latitude)) {
            $this->logger->error('Niepomyślna walidacja parametru latitude');

            throw new \Exception('Parametr latitude jest niepoprawny!', HttpCodes::HTTP_BAD_REQUEST);
        }

        if (isset($parameters->longitude) && !preg_match(RegularExpression::REGEX_COORDINATES, $parameters->longitude)) {
            $this->logger->error('Niepomyślna walidacja parametru longitude');

            throw new \Exception('Parametr longitude jest niepoprawny!', HttpCodes::HTTP_BAD_REQUEST);
        }

        return true;
    }
}
