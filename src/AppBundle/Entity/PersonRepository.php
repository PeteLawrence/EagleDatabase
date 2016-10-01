<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{
    public function findMembersAtDate($date)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM AppBundle:Person p
                JOIN p.memberRegistration mr
                JOIN mr.membershipTypePeriod mtp
                JOIN mtp.membershipPeriod mp
                WHERE ?1 BETWEEN mp.fromDate AND mp.toDate
                AND mr.registrationDateTime <= ?1
            ')
            ->setParameter(1, $date)
            ->getResult();
    }
}
