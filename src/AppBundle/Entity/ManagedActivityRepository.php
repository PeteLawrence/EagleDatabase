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



    public function findUpcomingActivities($person)
    {
        $now = new \DateTime();

        $q = $this->createQueryBuilder('ma')
            ->innerJoin('ma.participant', 'pa')
            ->innerJoin('pa.person', 'p')
            ->where('p.id = ?1')
            ->andWhere('ma.activityStart >= ?2')
            ->setParameter(1, $person->getId())
            ->setParameter(2, $now)
            ->orderBy('ma.activityStart');

        $a = $q->getQuery()->getResult();

        return $a;
    }
}
