<?php declare(strict_types=1);

namespace DataProvider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class DataProvider implements DataProviderInterface, LoggerAwareInterface
{
    private $client;
    private $logger;
    private $url;
    private $user;
    private $password;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->logger = new NullLogger();
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function setParameters(string $url, ?string $user = null, ?string $password = null): void
    {
        $this->url = $url;
        $this->user = $user;
        $this->password = $password;

        $this->logger->debug(sprintf('DataProvider parameters set: url=%s, user=%s, password=%s', $url, $user, $password));
    }

    private function validateParameters()
    {
        if (empty($this->url)) {
            throw new \RuntimeException('Call setParameters before getResponse');
        }
    }

    public function getResponse(array $input): string
    {
        $this->validateParameters();

        $this->logger->debug('DataProvider requested params: '.var_export($input, true));

        try {
            $options = ['query' => $input];

            if (!empty($this->user) || !empty($this->password)) {
                $options['auth'] = [$this->user, $this->password];
            }

            sleep(1); // Delay for demo

            $clientResponse = $this->client->get($this->url, $options);

            $this->logger->debug('DataProvider received: '.$clientResponse->getBody()->__toString());

            return $clientResponse->getBody()->__toString();
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());

            throw new DataProviderException($e->getMessage(), $e->getCode(), $e);
        }

    }
}
