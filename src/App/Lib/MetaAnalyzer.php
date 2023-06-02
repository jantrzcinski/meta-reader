<?php

declare(strict_types=1);

namespace App\Lib;

use App\Lib\Config;

class MetaAnalyzer
{
    public function analyze(array $data){
        $limitTitle = Config::get('LIMIT_TITLE');
        $limitDescription = Config::get('LIMIT_DESCRIPTION');
    }


}
