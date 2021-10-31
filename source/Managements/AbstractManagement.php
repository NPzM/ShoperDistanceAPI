<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Managements;

use ShoperPL\ShoperDistanceAPI\Logger;

abstract class AbstractManagement
{
    public function __construct()
    {
        Logger::enableSystemLogs();
        $this->logger = Logger::getInstance();
    }

    public function run()
    {
        Logger::enableSystemLogs();
    }
}
