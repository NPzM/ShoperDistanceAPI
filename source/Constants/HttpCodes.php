<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Constants;

/**
 * Klasa definiująca możliwe statusy HTTP.
 */
class HttpCodes
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_CONFLICT = 409;
    const HTTP_SERVICE_UNAVAILABLE = 503;
}
