<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Lib\MetaParser;
use App\Lib\MetaAnalyzer;
use App\Lib\Config;
use App\Lib\Log;
use GuzzleHttp\Exception\ConnectException;

class MetaController extends BaseController
{
    public function actionIndex()
    {
        $limitTitle = Config::get('LIMIT_TITLE');
        $limitDescription = Config::get('LIMIT_DESCRIPTION');

        $log = Log::getInstance();
        $lastLogs = $log->get();

        $this->render('meta/index', ['limitTitle' => $limitTitle, 'limitDescription' => $limitDescription, 'lastLogs' => $lastLogs]);
    }

    public function actionMeta(): void
    {
        $data = [];
        $error = [];

        $json = file_get_contents("php://input"); // json string
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $url = $data['url'] ?? '';

        if (!preg_match("~^(?:f|ht)tps?://~i", (string) $url)) {
            $url = "http://" . $url;
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $data['host'] = parse_url((string) $url, PHP_URL_HOST);
            try {
                $client = new \GuzzleHttp\Client(['verify' => false]);
                $res = $client->request('GET', $url);

                $metaParser = new MetaParser((string) $res->getBody());
                $metaParser->parse();

                $metaAnalyzer = new MetaAnalyzer($metaParser->getMeta());
                $data = array_merge($metaAnalyzer->getCalculatedData(), $data);

                $log = Log::getInstance();
                $log->write($url);
            } catch (ConnectException $e) {
                $error[] = $e->getMessage();
            }
        } else {
            $error[] =  "$url is not a valid URL";
        }

        $response = ['data' => $data, 'error' => $error];
        $this->sendJsonResponse($response);
    }
}
