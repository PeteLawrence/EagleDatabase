<?php

namespace AppBundle\Services;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\MemberRegistration;

class PersonService
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function forgetPerson($person)
    {
        $person->setForename('Expired');
        $person->setSurname('Member');
        $person->setEmail('expired@eaglecanoeclub.co.uk');
        $person->setPassword('EXPIRED');
        $person->setGender(null);
        $person->setDob(null);
        $person->setAddr1('');
        $person->setAddr2('');
        $person->setAddr3('');
        $person->setTown('');
        $person->setPostcode('');
        $person->setCounty('');
        $person->setTelephone('');
        $person->setMobile('');
        $person->setNotes('');
        $person->setNextOfKinName('');
        $person->setNextOfKinRelation('');
        $person->setNextOfKinContactDetails('');
        $person->setBcMembershipNumber('');
        $person->setForgotten(true);
        $person->setForgottenDateTime(new \DateTime());
        $person->setDoNotContact(true);

        $this->em->flush();
    }

}
