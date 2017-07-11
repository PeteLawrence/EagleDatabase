<?php

namespace AppBundle\Services;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\MemberRegistration;

class GroupService
{
    private $em;
    private $formFactory;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\Form\FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }


    public function buildRegularEmailsList($group)
    {
        $emails = [];
        
        foreach ($group->getPerson() as $person) {
            $email = $person->getEmail();

            if (strpos($email, '@btinternet') == false) {
                $emails[] = $email;
            }
        }

        $emails = array_unique($emails);
        $emailsString = implode('; ', $emails);

        return $emailsString;
    }

    public function buildBtEmailsList($group)
    {
        $emails = [];
        foreach ($group->getPerson() as $person) {
            $email = $person->getEmail();

            if (strpos($email, '@btinternet')) {
                $emails[] = $email;
            }
        }

        $emails = array_unique($emails);
        $emailsString = implode('; ', $emails);

        return $emailsString;
    }
}
