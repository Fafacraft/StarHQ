<?php

namespace App\Repository;

use App\Entity\Ship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ship>
 */
class ShipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ship::class);
    }

    //    /**
    //     * @return Ship[] Returns an array of Ship objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findOneByName($name): ?Ship
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.Name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // search for a ship name with this specific patern
    public function findByNameLike(string $name)
    {
        return $this->createQueryBuilder('s')
            ->where('s.Name LIKE LOWER(:name)')
            ->setParameter('name', '%' . $name . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
