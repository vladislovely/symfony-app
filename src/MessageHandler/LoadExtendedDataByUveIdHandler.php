<?php

namespace App\MessageHandler;

use App\Entity\StandardUnitValue;
use App\Message\LoadExtendedDataByUveId;
use App\Message\ProcessStandardUnitValueGrade;
use App\Message\ProcessStandardUnitValueNpe;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class LoadExtendedDataByUveIdHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $bus
    ) {}

    public function __invoke(LoadExtendedDataByUveId $message): void
    {
        $stack = HandlerStack::create();
        $stack->push(GuzzleRetryMiddleware::factory([
            'max_retry_attempts' => 5,
            'retry_on_status'    => [429, 502, 500, 504],
            'retry_on_timeout'   => true,
            'connect_timeout'    => 10,
            'timeout'            => 5,
        ]));

        $client = new Client([
            'headers'  => [
                'Accept' => 'application/json',
            ],
            'handler'  => $stack,
        ]);

        try {
            $uveId = $message->getUveId();
            $response = $client->request('GET', $message->getUri());

            $decodedResponse = json_decode($response->getBody()->getContents(),
                true, 512, JSON_THROW_ON_ERROR);

            if (isset($decodedResponse['passport']['interval'])) {
                $entity = $this->getStandardUnitValueByUveId($uveId);
                $entity?->setInterval((int) $decodedResponse['passport']['interval']);
            } else {
                $this->logger->info($uveId . ' пустой или нет passport->interval');
            }

            if (isset($decodedResponse['passport']['rank'])) {
                $this->bus->dispatch(new ProcessStandardUnitValueGrade(
                    rank: $decodedResponse['passport']['rank'],
                    uveId: $uveId
                ));
            } else {
                $this->logger->info('По uve_id - '. $uveId . ' не был определен разряд эталона из внутреннего справочника');
            }

            if (isset($decodedResponse['general']['npe']) && count($decodedResponse['general']['npe']) > 0) {
                $this->bus->dispatch(new ProcessStandardUnitValueNpe(
                    npes: $decodedResponse['general']['npe'],
                    uveId: $uveId
                ));
            } else {
                $this->logger->info('По uve_id - '. $uveId . ' не был определен ГПЭ из внутреннего справочника');
            }
        } catch (GuzzleException $e) {
            echo $e->getMessage() . "\n";
            echo $e->getRequest()->getMethod();
        }
    }

    private function getStandardUnitValueByUveId(int $uveId): ?StandardUnitValue
    {
        return $this->entityManager->getRepository(StandardUnitValue::class)->findOneBy(['uveId' => $uveId]);
    }
}
