<?php

declare(strict_types=1);

namespace App\Lib;

class Config
{
    private static $config;

    public static function get($key, $default = null)
    {
        if (is_null(self::$config)) {
            self::$config = require_once(dirname(__FILE__, 4) . '/config.php');
           
        }

        return empty(self::$config[$key]) ? $default : self::$config[$key];
    }
}
