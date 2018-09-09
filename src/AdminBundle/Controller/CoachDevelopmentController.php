<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Services\PersonService;

/**
 * Qualification controller.
 *
 * @Route("/coachdevelopment")
 */
class CoachDevelopmentController extends Controller
{
    /**
     * Coach Development Index
     *
     * @Route("/", name="admin_coachdevelopment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('admin/coachdevelopment/index.html.twig');
    }


    /**
     * Coaches Qualifications Grid
     *
     * @Route("/coaches", name="admin_coachdevelopment_coaches")
     * @Method("GET")
     */
    public function coachesAction(PersonService $personService)
    {
        $em = $this->get('doctrine')->getManager();
        $coachType = $em->getRepository('AppBundle:MembershipType')->findOneByType('Coach');
        $coaches = $em->getRepository('AppBundle:Person')->findMembersByType($coachType);

        return $this->render('admin/coachdevelopment/coaches.html.twig', [
            'coaches' => $coaches
        ]);
    }
}
