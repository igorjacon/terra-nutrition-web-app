<?php

namespace App\Repository;

use App\Entity\MealOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MealOption>
 *
 * @method MealOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealOption[]    findAll()
 * @method MealOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MealOption::class);
    }

    //    /**
    //     * @return MealOption[] Returns an array of MealOption objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MealOption
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
