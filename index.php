<?php

declare(strict_types=1);

use ShoperPL\ShoperDistanceAPI\Config;
use ShoperPL\ShoperDistanceAPI\Controller;
use ShoperPL\ShoperDistanceAPI\Logger;

require __DIR__ . '/vendor/autoload.php';

$LOG_PATH = Config::get('LOG_PATH', '');
echo "[LOG_PATH]: $LOG_PATH";

Logger::enableSystemLogs();
$logger = Logger::getInstance();
$logger->info('Hello World');

Controller::run();