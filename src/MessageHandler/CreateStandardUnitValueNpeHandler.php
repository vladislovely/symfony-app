<?php

namespace App\MessageHandler;

use App\Entity\StandardUnitValueNpe;
use App\Message\CreateStandardUnitValueNpe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateStandardUnitValueNpeHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(CreateStandardUnitValueNpe $message): void
    {
        $this->entityManager->beginTransaction();

        $entity = new StandardUnitValueNpe();
        $entity->setStandardStatePrimaryId($message->getStandardStatePrimaryId());
        $entity->setStandardUnitValueId($message->getUveId());
        $entity->setOrderId($message->getOrder());
        $entity->setCreatedAtValue();
        $entity->setUpdatedAtValue();
        $entity->setDeletedAt(null);

        try {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->rollBack();
            throw $e;
        }
    }
}
