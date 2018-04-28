<?php declare(strict_types=1);

namespace CachingDataProvider;

use DataProvider\DataProviderInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CachingDataProvider implements DataProviderInterface, LoggerAwareInterface
{
    private $provider;
    private $cachePool;
    private $cacheTtl;
    private $logger;

    public function __construct(DataProviderInterface $provider, CacheItemPoolInterface $cachePool)
    {
        $this->provider = $provider;
        $this->cachePool = $cachePool;
        $this->cacheTtl = null;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setParameters(string $url, ?string $user = null, ?string $password = null): void
    {
        $this->provider->setParameters($url, $user, $password);
    }

    public function setCacheTtl(int $seconds): void
    {
        $this->cacheTtl = $seconds;
    }

    public function getResponse(array $input): string
    {
        $this->logger->debug('CachingDataProvider requested params: '.var_export($input, true));

        $cacheKey = $this->getCacheKey($input);

        try {
            $cacheItem = $this->cachePool->getItem($cacheKey);

            if (!$cacheItem->isHit()) {
                $this->logger->debug('CachingDataProvider: key NOT FOUND in the cache pool');
                $value = $this->provider->getResponse($input);
                $cacheItem->set($value);
                $this->cachePool->save($cacheItem);
            } else {
                $this->logger->debug('CachingDataProvider: key FOUND in the cache pool');
            }

            return $cacheItem->get();
        } catch (\Psr\Cache\CacheException $e) {
            $this->logger->critical('CachingDataProvider: cache error '.$e->getMessage().' '.$e->getCode());

            return $this->provider->getResponse($input);
        }
    }

    private function getCacheKey(array $input)
    {
        if (empty($input)) {
            return 'EMPTY_KEY';
        }

        $keys = array_keys($input);
        sort($keys);
        $sortedInput = [];
        foreach ($keys as $key) {
            $sortedInput = $input[$key];
        }

        return md5(\serialize([
            'input' => $sortedInput,
            'params' => [$this->getUrl(), $this->getUser(), $this->getPassword()]
        ]));
    }

    public function getUrl(): string
    {
        return $this->provider->getUrl();
    }

    public function getUser(): ?string
    {
        return $this->provider->getUser();
    }

    public function getPassword(): ?string
    {
        return $this->provider->getPassword();
    }
}
