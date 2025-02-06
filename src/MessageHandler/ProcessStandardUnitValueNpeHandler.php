<?php

namespace App\MessageHandler;

use App\Entity\StandardStatePrimary;
use App\Entity\StandardUnitValueGrade;
use App\Message\CreateStandardUnitValueNpe;
use App\Message\ProcessStandardUnitValueNpe;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class ProcessStandardUnitValueNpeHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $bus,
    ) {}

    public function __invoke(ProcessStandardUnitValueNpe $message): void
    {
        foreach ($message->getNpes() as $npe) {
            if (isset($npe['npe_number']) === false) {
                $this->logger->warning('В uve_id: '. $message->getUveId() . ' пустой npe: ' . json_encode($npe));

                continue;
            }

            $npeNumber = $npe['npe_number'];

            $primary = $this->getStandardStatePrimaryByNpeNumber($npeNumber);

            if ($primary === null) {
                $this->logger->warning('В таблице - standard_state_primary нет записи с npe_number - ' . $npeNumber . ', по uve_id - ' . $message->getUveId());

                continue;
            }

            $standardUnitValueGradeOrderID = $this->getStandardUnitValueGradeOrder($message->getUveId());
            $orderID = $standardUnitValueGradeOrderID ? $standardUnitValueGradeOrderID + 10 : 10;
            $this->logger->info('i am suvnpe');

            $this->bus->dispatch(new CreateStandardUnitValueNpe(
                standardStatePrimaryId: $primary->getId(),
                uveId: $message->getUveId(),
                order: $orderID
            ));
        }
    }

    private function getStandardStatePrimaryByNpeNumber(string $npeNumber): ?StandardStatePrimary
    {
        return $this->entityManager->getRepository(StandardStatePrimary::class)->findOneBy(['number' => $npeNumber]);
    }

    private function getStandardUnitValueGradeOrder(int $uveId): ?int
    {
        return $this->entityManager->getRepository(StandardUnitValueGrade::class)
            ->createQueryBuilder('suvg')
            ->select('MAX(suvg.orderId)')
            ->where('suvg.standardUnitValueId = :uveId')
            ->setParameter('uveId', $uveId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
