<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Controllers\MetaController;
use App\Lib\Config;

//$LOG_PATH = Config::get('LOG_PATH', '');

// $app = new MetaController;
// $app->actionIndex();

use App\Core\Framework;

$app = new Framework();

$app::get('/', 'MetaController', 'actionIndex');
$app::post('/meta', 'MetaController', 'actionMeta');
$app->run();
