<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{
    public function findMembersAtDate($date)
    {
        return $this->queryMembersAtDate($date)
            ->getQuery()
            ->getResult();
    }


    public function queryMembersAtDate($date)
    {
        $query = $this->createQueryBuilder('p')
            ->innerJoin('p.memberRegistration', 'mr')
            ->innerJoin('mr.membershipTypePeriod', 'mtp')
            ->innerJoin('mtp.membershipPeriod', 'mp')
            ->where('?1 BETWEEN mp.fromDate AND mp.toDate')
            ->andWhere('mr.registrationDateTime <= ?1')
            ->setParameter(1, $date);

        return $query;
    }
}
