<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CarRepository extends EntityRepository
{
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