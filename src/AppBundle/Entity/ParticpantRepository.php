<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ParticipantRepository extends EntityRepository
{
    /**
     * Returns activities that a person has attended/signed up for
     *
     * @param  [type] $person [description]
     *
     * @return [type]         [description]
     */
    public function findByPerson($person)
    {
        $q = $this->createQueryBuilder('p')
            ->innerJoin('p.managedActivity', 'ma')
            ->where('p.person = ?3')
            ->setParameter(1, $person)
            ->orderBy('ma.activityStart');
    }
}
