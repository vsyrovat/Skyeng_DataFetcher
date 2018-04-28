<?php declare(strict_types=1);

require_once 'bootstrap.php';

use CustomFetchers\CoindescFetcher;

$fetcher = new CoindescFetcher($provider);

$data = $fetcher->fetchRates();

print_r($data);
