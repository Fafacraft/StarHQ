<?php

namespace App\Repository;

use App\Entity\ShipPersonal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShipPersonal>
 */
class ShipPersonalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShipPersonal::class);
    }

    //    /**
    //     * @return ShipPersonal[] Returns an array of ShipPersonal objects
    //     */
    public function findByUser($user): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.email = :val')
            ->setParameter('val', $user)
            ->orderBy('s.priority', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?ShipPersonal
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
