<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use DataProvider\DataProvider;
use CustomFetchers\CoindescFetcher;

$client = new Client();
$logger = new \Monolog\Logger('log');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__.'/../log/coindesc.log', \Monolog\Logger::DEBUG));

$provider = new DataProvider($client);
$provider->setLogger($logger);

$fetcher = new CoindescFetcher($provider);

$data = $fetcher->fetchRates();

print_r($data);
