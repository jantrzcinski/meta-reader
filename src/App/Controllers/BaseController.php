<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Lib\Config;

abstract class BaseController
{
    public function render(string $file, $variables = []): void
    {
        $basePath = Config::get('BASE_PATH', '/');
        $assetPath = Config::get('ASSET_PATH', '/');

        extract($variables);

        include dirname(__FILE__) . '/../Views/' . $file . '.php';
    }

    public function sendJsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
