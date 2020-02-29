<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{

    public function queryAll()
    {
        $query = $this->createQueryBuilder('p')
        ->orderBy('p.forename')
        ->addOrderBy('p.surname');

        return $query;
    }


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
            ->orderBy('p.forename')
            ->addOrderBy('p.surname')
            ->setParameter(1, $date);

        return $query;
    }


    public function findMembersByType($type, $date = null)
    {
        if ($date == null) {
            $date = new \DateTime();
        }

        return $this->queryMembersByType($type, $date)
            ->getQuery()
            ->getResult();
    }


    public function queryMembersByType($type, $date)
    {
        $q = $this->createQueryBuilder('p')
            ->innerJoin('p.memberRegistration', 'mr')
            ->innerJoin('mr.membershipTypePeriod', 'mtp')
            ->innerJoin('mtp.membershipPeriod', 'mp')
            ->where('?1 BETWEEN mp.fromDate AND mp.toDate')
            ->andWhere('mr.registrationDateTime <= ?1')
            ->andWhere('mtp.membershipType IN (?2)')
            ->orderBy('p.forename')
            ->addOrderBy('p.surname')
            ->setParameter(1, $date)
            ->setParameter(2, $type);
        return $q;
    }


    public function findMembersByEmailAndDob($email, $dob)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.email = ?1')
            ->andWhere('p.dob = ?2')
            ->setParameter(1, $email)
            ->setParameter(2, $dob->format('Y-m-d'))
        ;

        return $query->getQuery()->getResult();
    }



    public function findMembersByMembershipPeriod($membershipPeriod)
    {
        $query = $this->createQueryBuilder('p')
            ->innerJoin('p.memberRegistration', 'mr')
            ->innerJoin('mr.membershipTypePeriod', 'mtp')
            ->where('mtp.membershipPeriod = ?1')
            ->setParameter(1, $membershipPeriod)
        ;

        return $query->getQuery()->getResult();
    }


    public function findMembersNotEnrolledSince($date)
    {
        $q1 = $this->createQueryBuilder('p1')
            ->select('p1.id')
            ->innerJoin('p1.memberRegistration', 'mr')
            ->where('mr.registrationDateTime > ?1');

        $q2 = $this->createQueryBuilder('p2');
        $q2->where($q2->expr()->notIn('p2.id', $q1->getDQL()))
            ->setParameter(1, $date);

        return $q2->getQuery()->getResult();
    }


    public function findMembersWithQualification($qualification)
    {
        $now = new \DateTime();

        $query = $this->createQueryBuilder('p')
            ->innerJoin('p.memberRegistration', 'mr')
            ->innerJoin('mr.membershipTypePeriod', 'mtp')
            ->innerJoin('mtp.membershipPeriod', 'mp')
            ->innerJoin('p.memberQualification', 'mq')
            ->where('?1 BETWEEN mp.fromDate AND mp.toDate')
            ->andWhere('mr.registrationDateTime <= ?1')
            ->andWhere('mq.qualification = ?2')
            ->setParameter(1, $now)
            ->setParameter(2, $qualification)
        ;

        return $query->getQuery()->getResult();
    }



    public function queryAdmins()
    {
        $q = $this->createQueryBuilder('p')
            ->where('p.admin = ?1')
            ->orderBy('p.forename')
            ->addOrderBy('p.surname')
            ->setParameter(1, true);
        return $q;
    }
}
