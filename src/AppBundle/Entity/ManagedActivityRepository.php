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


    public function findActivitiesBetweenDates($from, $to, $activityType)
    {
        return $this->queryActivitiesBetweenDates($from, $to, $activityType)->getQuery()->getResult();
    }

    public function queryActivitiesBetweenDates($from, $to, $activityType)
    {
        $q = $this->createQueryBuilder('ma')
            ->where('ma.activityStart >= ?1')
            ->andWhere('ma.activityStart < ?2')
            ->setParameter(1, $from)
            ->setParameter(2, $to)
            ->orderBy('ma.activityStart');

        if ($activityType) {
            $q->andWhere('ma.activityType = ?3');
            $q->setParameter(3, $activityType);
        }

        return $q;
    }



    public function findUpcomingActivities($person, $count)
    {
        $now = new \DateTime();

        $q = $this->createQueryBuilder('ma')
            ->innerJoin('ma.participant', 'pa')
            ->innerJoin('pa.person', 'p')
            ->where('p.id = ?1')
            ->andWhere('ma.activityStart >= ?2')
            ->setParameter(1, $person->getId())
            ->setParameter(2, $now)
            ->orderBy('ma.activityStart')
            ->setMaxResults($count);

        $a = $q->getQuery()->getResult();

        return $a;
    }


    public function findNextActivities($count)
    {
        $now = new \DateTime();

        $q = $this->createQueryBuilder('ma')
            ->where('ma.activityStart >= ?1')
            ->setParameter(1, $now)
            ->orderBy('ma.activityStart')
            ->setMaxResults($count);

        return $q->getQuery()->getResult();
    }
}
