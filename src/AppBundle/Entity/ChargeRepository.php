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


    public function findPaymentsBetween($fromDate, $toDate)
    {
        $query = $this->createQueryBuilder('c')
                ->where('c.paiddatetime >= ?1')
                ->andWhere('c.paiddatetime < ?2')
                ->andWhere('c.paid = ?3')
                ->setParameter(1, $fromDate)
                ->setParameter(2, $toDate)
                ->setParameter(3, true);

        return $query->getQuery()->getResult();
    }
}
