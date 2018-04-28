<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use DataProvider\DataProvider;

$client = new Client();
$logger = new \Monolog\Logger('app');
$logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__.'/../log/app.log', \Monolog\Logger::DEBUG));

$provider = new DataProvider($client);
$provider->setLogger($logger);
