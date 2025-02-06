<?php

namespace App\MessageHandler;

use App\Entity\StandardUnitValue;
use App\Message\CreateStandardUnitValue;
use App\Message\LoadExtendedDataByUveId;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class CreateStandardUnitValueHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(CreateStandardUnitValue $message): void
    {
        $this->entityManager->beginTransaction();

        $entity = new StandardUnitValue();
        $entity->setUveId($message->getUveDataDto()->uveId);
        $entity->setActDate($message->getUveDataDto()->actDate);
        $entity->setActNo($message->getUveDataDto()->actNo);
        $entity->setInterval($message->getUveDataDto()->interval);
        $entity->setTitle($message->getUveDataDto()->title);
        $entity->setNumber($message->getUveDataDto()->number);
        $entity->setCreatedAtValue();

        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->rollBack();
            throw $e;
        }

        $this->bus->dispatch(new LoadExtendedDataByUveId(
            uri: $message->getUrl(),
            uveId: $message->getUveDataDto()->uveId
        ));
    }
}
