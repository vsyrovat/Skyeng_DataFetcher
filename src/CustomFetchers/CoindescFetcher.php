<?php declare(strict_types=1);

namespace CustomFetchers;

use DataProvider\DataProviderInterface;

class CoindescFetcher
{
    private $provider;

    public function __construct(DataProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function fetchRates()
    {
        $this->provider->setParameters('https://api.coindesk.com/v1/bpi/currentprice.json');

        return $this->convert(
            $this->provider->getResponse(['foo' => 'bar'])
        );
    }

    private function convert($string)
    {
        $data = \GuzzleHttp\json_decode($string, true);

        $result = [];

        foreach ($data['bpi'] as $currency => $info) {
            $result[$currency] = $info['rate_float'];
        }

        return $result;
    }
}
