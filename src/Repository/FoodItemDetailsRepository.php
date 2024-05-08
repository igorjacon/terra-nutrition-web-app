<?php

namespace App\Repository;

use App\Entity\FoodItemDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FoodItemDetails>
 *
 * @method FoodItemDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodItemDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodItemDetails[]    findAll()
 * @method FoodItemDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodItemDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodItemDetails::class);
    }

    //    /**
    //     * @return FoodItemDetails[] Returns an array of FoodItemDetails objects
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

    //    public function findOneBySomeField($value): ?FoodItemDetails
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
