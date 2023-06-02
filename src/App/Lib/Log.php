<?php

declare(strict_types=1);

namespace App\Lib;

use App\Lib\Config;

class Log
{
    private static $instance;
    protected static $file;

    public static function getInstance()
    {
        self::$file = Config::get('LOG_PATH');

        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function write($message)
    {
        if ($message == '') return;

        $message = $message . '|' . date('Y-m-d H:i:s');
        $messages = explode(PHP_EOL, file_get_contents(self::$file)) ?? [];

        array_unshift($messages, $message);

        $output = array_slice($messages, 0, 10);
        file_put_contents(self::$file, implode(PHP_EOL, $output));
    }

    public function get(int $limit = 10): ?array
    {
        $messages = explode(PHP_EOL, file_get_contents(self::$file));
        array_walk($messages, function (&$e) { 
            $e = explode('|', $e);
        });

        return $messages;
    }
}
