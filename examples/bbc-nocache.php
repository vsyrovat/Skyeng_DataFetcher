<?php declare(strict_types=1);

require_once 'bootstrap.php';

use CustomFetchers\BBCFetcher;

$fetcher = new BBCFetcher($provider);

$data = $fetcher->fetchNews();

print_r($data);
