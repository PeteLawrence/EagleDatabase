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
        $membershipType = 0;
        if ($person != 'anon.' && $person->getCurrentMemberRegistration()) {
            $membershipType = $person->getCurrentMemberRegistration()->getMembershipTypePeriod()->getMembershipType()->getType();
        }


        $query = $this->createQueryBuilder('ma')
            ->leftJoin('ma.managedActivityMembershipType', 'mamt')
            ->innerJoin('mamt.membershipType', 'mt')
            ->where('mt.type = ?1')
            ->setParameter(1, $membershipType);

        return $query;
    }
}
