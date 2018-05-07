<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        //Set the lastLoginDateTime on the user
        $user = $event->getAuthenticationToken()->getUser();
        $user->setLastLoginDateTime(new \DateTime());

        //FLush changes to DB
        $this->em->flush();
    }
}
