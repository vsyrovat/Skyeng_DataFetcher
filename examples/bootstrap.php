<?php declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use DataProvider\DataProvider;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$client = new Client();
$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(__DIR__.'/../log/app.log', Logger::DEBUG));

$provider = new DataProvider($client);
$provider->setLogger($logger);
