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

    // find a specific shipPersonal
    public function findPrecise($user, $name, $priority): ?ShipPersonal
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.email = :email')
            ->andWhere('s.name = :name')
            ->andWhere('s.priority = :priority OR s.priority IS NULL')
            ->setParameter('email', $user)
            ->setParameter('name', $name)
            ->setParameter('priority', $priority)
            ->orderBy('s.priority', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getResult()[0]
        ;
    }

    // number of ship for user
    public function findNbShips($user, $name): ?int
    {
        return $this->createQueryBuilder('s')
            ->select('count(s.id)')
            ->andWhere('s.email = :email')
            ->andWhere('s.name = :name')
            ->setParameter('email', $user)
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
