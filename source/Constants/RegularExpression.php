<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Constants;

/**
 * Klasa definiująca wyrażenia regularne.
 */
class RegularExpression
{
    const REGEX_COORDINATES = '/^(\-?|\+?)?\d+(\.\d+)?$/';
    const REGEX_CITY = '/^[a-zA-Z]*$/';
    const REGEX_STREET = '/^[a-zA-Z0-9 ]*$/';
}
