<?php

declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI\Managements;

use ShoperPL\ShoperDistanceAPI\Logger;
use ShoperPL\ShoperDistanceAPI\Repository\Database;

abstract class AbstractManagement
{
    /**
    * @var Database
    */
    protected $database;

    public function __construct()
    {
        Logger::enableSystemLogs();
        $this->logger = Logger::getInstance();
        $this->database = new Database();
    }

    public function run()
    {
        Logger::enableSystemLogs();
    }
}
