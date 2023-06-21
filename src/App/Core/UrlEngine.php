<?php

declare(strict_types=1);

namespace App\Core;
use App\Lib\Config;

trait UrlEngine
{
    public function method(): string
    {
        return strtolower((string) $_SERVER['REQUEST_METHOD']);
    }

    public function path()
    {
        return str_replace(rtrim((string) Config::get('BASE_PATH', ''), '/'), '', (string) $_SERVER['REQUEST_URI']);
    }
}
