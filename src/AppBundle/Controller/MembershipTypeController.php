<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MembershipTypeController extends Controller
{
    /**
     * @Route("/membershiptype")
     */
    public function indexAction(Request $request)
    {
        $membershipType = new \AppBundle\Entity\MembershipType;
        $form = $this->createForm(\AppBundle\Form\MembershipTypeForm::class, $membershipType);

        return $this->render('default/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
