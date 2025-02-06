<?php

namespace App\MessageHandler;

use App\Entity\StandardUnitValue;
use App\Message\LoadExtendedDataByUveId;
use App\Message\ProcessStandardUnitValueGrade;
use App\Message\ProcessStandardUnitValueNpe;
use Doctrine\ORM\EntityManagerInterface;
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
        $url = $message->getUri();

        $client = new \GuzzleHttp\Client();
        $this->logger->info('i am here');
        $this->logger->info($url);

        $promise = $client->requestAsync('GET', $url);
        $promise->then(
            function (ResponseInterface $res) use ($message) {
                $decodedResponse = json_decode($res->getBody()->getContents(),
                    true, 512, JSON_THROW_ON_ERROR);

                if (isset($decodedResponse['passport']['interval'])) {
                    $entity = $this->getStandardUnitValueByUveId($message->getUveId());
                    $entity?->setInterval((int) $decodedResponse['passport']['interval']);
                } else {
                    $this->logger->info($message->getUveId() . ' пустой или нет passport->interval');
                }

                if (isset($decodedResponse['passport']['rank'])) {
                    $this->bus->dispatch(new ProcessStandardUnitValueGrade(
                        rank: $decodedResponse['passport']['rank'],
                        uveId: $message->getUveId()
                    ));
                } else {
                    $this->logger->info('По uve_id - '. $message->getUveId() . ' не был определен разряд эталона из внутреннего справочника');
                }

                if (isset($decodedResponse['general']['npe']) && count($decodedResponse['general']['npe']) > 0) {
                    $this->bus->dispatch(new ProcessStandardUnitValueNpe(
                        npes: $decodedResponse['general']['npe'],
                        uveId: $message->getUveId()
                    ));
                } else {
                    $this->logger->info('По uve_id - '. $message->getUveId() . ' не был определен ГПЭ из внутреннего справочника');
                }
            },
            function (RequestException $e) {
                echo $e->getMessage() . "\n";
                echo $e->getRequest()->getMethod();
            },
        );

        $promise->wait();
    }

    private function getStandardUnitValueByUveId(int $uveId): ?StandardUnitValue
    {
        return $this->entityManager->getRepository(StandardUnitValue::class)->findOneBy(['uveId' => $uveId]);
    }
}
