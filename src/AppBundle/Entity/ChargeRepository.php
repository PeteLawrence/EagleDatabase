<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ChargeRepository extends EntityRepository
{
    public function findDuePayments()
    {
        return $this->queryDuePayments()
            ->getQuery()
            ->getResult();
    }


    public function queryDuePayments()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.paid = ?1')
            ->setParameter(1, false);

        return $query;
    }

    public function findOverduePayments()
    {
        return $this->queryOverduePayments()
                ->getQuery()
                ->getResult();
    }


    public function queryOverduePayments()
    {
        $query = $this->createQueryBuilder('c')
                ->where('c.paid = ?1')
                ->andWhere('c.duedatetime < ?2')
                ->setParameter(1, false)
                ->setParameter(2, new \DateTime());

        return $query;
    }
}
