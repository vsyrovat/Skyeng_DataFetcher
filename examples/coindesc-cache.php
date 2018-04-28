<?php declare(strict_types=1);

require_once 'bootstrap.php';

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use CachingDataProvider\CachingDataProvider;
use CustomFetchers\CoindescFetcher;

$filesystemAdapter = new Local(__DIR__.'/../cache/');
$filesystem = new Filesystem($filesystemAdapter);
$cachePool = new FilesystemCachePool($filesystem);

$wrapper = new CachingDataProvider($provider, $cachePool);
$wrapper->setLogger($logger);
$wrapper->setCacheTtl(10);

$fetcher = new CoindescFetcher($wrapper);

$data = $fetcher->fetchRates();

print_r($data);

echo "CACHE TTL = 10 sec\n";
