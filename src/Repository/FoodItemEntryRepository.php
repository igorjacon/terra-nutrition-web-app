<?php

namespace App\Repository;

use App\Entity\FoodItemEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FoodItemEntry>
 *
 * @method FoodItemEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodItemEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodItemEntry[]    findAll()
 * @method FoodItemEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodItemEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodItemEntry::class);
    }

    //    /**
    //     * @return FoodItemEntry[] Returns an array of FoodItemEntry objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FoodItemEntry
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
