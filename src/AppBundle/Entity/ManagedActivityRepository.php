<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ManagedActivityRepository extends EntityRepository
{
    public function findActivitiesAvailableToPerson($person)
    {
        return $this->queryActivitiesAvailableToPerson($person)
            ->getQuery()
            ->getResult();
    }


    public function queryActivitiesAvailableToPerson($person)
    {
        $query = $this->createQueryBuilder('ma')
            ->leftJoin('ma.managedActivityMembershipType', 'mamt')
            ->innerJoin('mamt.membershipType', 'mt')
            ->where('mt.type = ?1')
            ->setParameter(1, $person->getCurrentMemberRegistration()->getMembershipTypePeriod()->getMembershipType()->getType());

        return $query;
    }
}
