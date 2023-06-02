<?php

declare(strict_types=1);

namespace App\Core;
use App\Lib\Config;

trait UrlEngine
{
    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function path()
    {
        return str_replace(rtrim(Config::get('BASE_PATH', ''), '/'), '', $_SERVER['REQUEST_URI']);
    }
}
