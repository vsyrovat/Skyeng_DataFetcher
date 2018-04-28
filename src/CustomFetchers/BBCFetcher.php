<?php declare(strict_types=1);

namespace CustomFetchers;

use DataProvider\DataProviderInterface;

class BBCFetcher
{
    private $provider;

    public function __construct(DataProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function fetchNews()
    {
        $this->provider->setParameters('http://feeds.bbci.co.uk/news/rss.xml');

        return $this->convert(
            $this->provider->getResponse(['foo' => 'bar'])
        );
    }

    private function convert($string)
    {
        $xml = simplexml_load_string($string);

        $result = [];

        foreach ($xml->channel->item as $item) {
            $result[] = (string)$item->title;
        }

        return $result;
    }
}
