<?php

namespace App\Repository;

use App\Entity\FoodMeasurement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FoodMeasurement>
 *
 * @method FoodMeasurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodMeasurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodMeasurement[]    findAll()
 * @method FoodMeasurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodMeasurementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodMeasurement::class);
    }

    //    /**
    //     * @return FoodMeasurement[] Returns an array of FoodMeasurement objects
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

    //    public function findOneBySomeField($value): ?FoodMeasurement
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
