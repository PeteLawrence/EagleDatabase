<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MemberQualificationRepository extends EntityRepository
{
    /**
     * Returns activities that a person has attended/signed up for
     *
     * @param  [type] $person [description]
     *
     * @return [type]         [description]
     */
    public function findExpiringQualifications()
    {
        $now = new \DateTime();
        $limit = clone $now;
        $limit->sub(new \DateInterval('P3M'));

        $q = $this->createQueryBuilder('mq')
            ->where('mq.validTo < ?1')
            ->andWhere('mq.validTo > ?2')
            ->andWhere('mq.superseded = 0')
            ->setParameter(1, $limit)
            ->setParameter(2, $now)
            ->orderBy('mq.validTo');

        return $q->getQuery()->getResult();
    }


    /**
     * Returns activities that a person has attended/signed up for
     *
     * @param  [type] $person [description]
     *
     * @return [type]         [description]
     */
    public function findExpiredQualifications()
    {
        $now = new \DateTime();

        $q = $this->createQueryBuilder('mq')
            ->where('mq.validTo < ?1')
            ->andWhere('mq.superseded = 0')
            ->setParameter(1, $now)
            ->orderBy('mq.validTo');

        return $q->getQuery()->getResult();
    }


    /**
     * Returns activities that a person has attended/signed up for
     *
     * @param  [type] $person [description]
     *
     * @return [type]         [description]
     */
    public function finddQualificationsRequiringVerification()
    {

        $q = $this->createQueryBuilder('mq')
            ->innerJoin('mq.qualification', 'q')
            ->where('mq.verifiedBy IS NULL')
            ->andWhere('q.verificationRequired = ?1')
            ->setParameter(1, 1); //true

        return $q->getQuery()->getResult();
    }


    public function findMemberQualificationsByType($qualification, $includeSuperseded = false)
    {
        $q = $this->createQueryBuilder('mq')
            ->where('mq.qualification = ?1')
            ->setParameter(1, $qualification);

        if (!$includeSuperseded) {
            $q->andWhere('mq.superseded = ?2')
              ->setParameter(2, false);
        }

        return $q->getQuery()->getResult();
    }

}
