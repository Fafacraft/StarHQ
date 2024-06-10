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

    //Get all ship names and pledge links where pledge link is not null
    public function findAllPledgeLinks(): array
    {
        // Create the QueryBuilder instance
        $qb = $this->createQueryBuilder('s');

        // Build the query
        $qb->select('s.Name, s.PledgeLink')
            ->where($qb->expr()->isNotNull('s.PledgeLink'));

        // Execute the query and get the result
        return $qb->getQuery()->getResult();
    }

    // get all ships
    public function findAllShips()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.Name', 'ASC') // Order by name in ascending order
            ->getQuery()
            ->getResult();
    }

    
    // get all ships by role
    public function findAllShipsByRole($role)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.role = :role')
            ->setParameter('role', $role)
            ->orderBy('s.Name', 'ASC') // Order by name in ascending order
            ->getQuery()
            ->getResult();
    }

    // get all ships by size
    public function findAllShipsBySize($size)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.size = :size')
            ->setParameter('size', $size)
            ->orderBy('s.Name', 'ASC') // Order by name in ascending order
            ->getQuery()
            ->getResult();
    }

    // get all ships by manufacturer
    public function findAllShipsByManufacturer($manufacturer)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.manufacturer IN (:manufacturer)')
            ->setParameter('manufacturer', $manufacturer)
            ->orderBy('s.Name', 'ASC') // Order by name in ascending order
            ->getQuery()
            ->getResult();
    }
}
