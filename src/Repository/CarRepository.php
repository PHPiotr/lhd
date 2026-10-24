<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    /**
     * @return int
     */
    public function getCountAll()
    {
        $qb = $this->createQueryBuilder('car');
        $qb->select('count(car.id)');
        $count = $qb->getQuery()->getSingleScalarResult();

        return (int)$count;
    }
}
