<?php declare(strict_types=1);

require_once 'bootstrap.php';

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use CachingDataProvider\CachingDataProvider;
use CustomFetchers\BBCFetcher;

$filesystemAdapter = new Local(__DIR__.'/../cache/');
$filesystem = new Filesystem($filesystemAdapter);
$cachePool = new FilesystemCachePool($filesystem);

$wrapper = new CachingDataProvider($provider, $cachePool);
$wrapper->setLogger($logger);
$wrapper->setCacheTtl(5);

$fetcher = new BBCFetcher($wrapper);

$data = $fetcher->fetchNews();

print_r($data);

echo "CACHE TTL = 5 sec\n";
