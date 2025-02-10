<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractFgisComponent
{
    protected int $start = 0;
    protected int $rows = 100;
    protected Client $client;
    protected SymfonyStyle $io {
        set(SymfonyStyle $value) {
            $this->io = $value;
        }
    }

    protected int $insertErrorsCounter = 0;
    protected int $insertSuccessCounter = 0;

    public function __construct(
        protected LoggerInterface $logger,
        protected EntityManagerInterface $em,
        protected MessageBusInterface $bus
    )
    {
        $stack = HandlerStack::create();
        $stack->push(GuzzleRetryMiddleware::factory([
            'max_retry_attempts' => 5,
            'retry_on_status'    => [429, 502, 500, 504],
            'retry_on_timeout'   => true,
            'connect_timeout'    => 10,
            'timeout'            => 5,
        ]));

        $this->client = new Client([
            'base_uri' => $this->getBaseUrl(),
            'headers'  => [
                'Accept' => 'application/json',
            ],
            'handler'  => $stack,
        ]);
    }

    abstract protected function getBaseUrl(): string;

    public function request(string $uri, string $method = 'GET', array $params = []): array
    {
        try {
            $response = $this->client->request($method, $uri, $params);

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            }

            return [];
        } catch (\Exception $e) {
            echo $e->getMessage();

            return [];
        }
    }

    protected function merged(iterable ...$input): \Iterator
    {
        foreach ($input as $iterable) {
            yield from $iterable;
        }
    }

    protected function printLog(string $str): void
    {
        $this->logger->info($str);

        echo $str . PHP_EOL;
    }

    protected function printLogCountInsert(): void
    {
        $this->printLog('Количество добавленных записей:'.$this->insertSuccessCounter);
        $this->printLog('Количество неудачных записей:'.$this->insertErrorsCounter);
    }
}
