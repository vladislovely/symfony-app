<?php

namespace App\Repository;

use App\Entity\Hold;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Hold>
 *
 * @method Hold|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hold|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hold[]    findAll()
 * @method Hold[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hold::class);
    }

    public function save(Hold $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hold $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Hold[] Returns an array of Hold objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
     * @throws NonUniqueResultException
     */
    public function findHoldByBookCopyIdAndAccountId(string $book_copy_id, string $account_id): ?Hold
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.bookCopy = :book_copy_id')
            ->andWhere('h.account = :account_id')
            ->setParameter('book_copy_id', $book_copy_id)
            ->setParameter('account_id', $account_id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
