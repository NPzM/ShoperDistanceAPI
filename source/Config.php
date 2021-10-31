<?php
declare(strict_types=1);

namespace ShoperPL\ShoperDistanceAPI;

class Config
{
    private static $config;

    public static function get($key, $default = null)
    {
        if (is_null(self::$config)) {
            self::$config = require_once(__DIR__ . '/../config.php');
        }

        return !empty(self::$config[$key])?self::$config[$key]:$default;
    }
}
