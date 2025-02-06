<?php

namespace App\MessageHandler;

use App\Entity\StandardUnitValueGrade;
use App\Message\CreateStandardUnitValueGrade;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateStandardUnitValueGradeHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(CreateStandardUnitValueGrade $message): void
    {
        $this->entityManager->beginTransaction();

        $entity = new StandardUnitValueGrade();
        $entity->setGradeStandardId($message->getGradeStandardId());
        $entity->setStandardUnitValueId($message->getStandardUnitValueId());
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
