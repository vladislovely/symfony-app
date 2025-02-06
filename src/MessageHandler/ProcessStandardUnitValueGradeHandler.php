<?php

namespace App\MessageHandler;

use App\Entity\GradeStandard;
use App\Entity\StandardUnitValueGrade;
use App\Message\CreateStandardUnitValueGrade;
use App\Message\ProcessStandardUnitValueGrade;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class ProcessStandardUnitValueGradeHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly MessageBusInterface $bus,
    ) {}

    public function __invoke(ProcessStandardUnitValueGrade $message): void
    {
        $ranks = explode(';', $message->getRank());

        foreach ($ranks as $name) {
            $name = trim($name);

            $grade = $this->getGradeStandard($name);

            if ($grade === null) {
                $this->logger->info('В таблице - grade_standard неn записи с именем' . $name . ', по uve_id - ' . $message->getUveId());

                continue;
            }

            $standardUnitValueGradeOrderID = $this->getStandardUnitValueGradeOrder($message->getUveId());
            $orderID = $standardUnitValueGradeOrderID ? $standardUnitValueGradeOrderID + 10 : 10;
            $id = $grade->getId() ?? null;

            $this->logger->info('i am suvg');
            $this->bus->dispatch(new CreateStandardUnitValueGrade(
                standardUnitValueId: $message->getUveId(),
                gradeStandardId: $id,
                order: $orderID,
            ));
        }
    }

    private function getGradeStandard(string $name): ?GradeStandard
    {
        return $this->entityManager->getRepository(GradeStandard::class)->findOneBy(['name' => $name]);
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
