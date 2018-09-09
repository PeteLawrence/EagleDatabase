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


    /**
     * Lifeguards Qualifications Grid
     *
     * @Route("/lifeguards", name="admin_coachdevelopment_lifeguards")
     * @Method("GET")
     */
    public function lifeguardsAction(PersonService $personService)
    {
        $em = $this->get('doctrine')->getManager();
        $lifeguard = $em->getRepository('AppBundle:Qualification')->findOneByName('Pool Endorsement Lifeguard');
        $lifeguards = $em->getRepository('AppBundle:Person')->findMembersWithQualification($lifeguard);

        return $this->render('admin/coachdevelopment/lifeguards.html.twig', [
            'lifeguards' => $lifeguards
        ]);
    }


    /**
     * CAAs Qualifications Grid
     *
     * @Route("/caas", name="admin_coachdevelopment_caas")
     * @Method("GET")
     */
    public function caasAction(PersonService $personService)
    {
        $em = $this->get('doctrine')->getManager();
        $caa = $em->getRepository('AppBundle:Qualification')->findOneByName('Club Activity Assistant');
        $caas = $em->getRepository('AppBundle:Person')->findMembersWithQualification($caa);

        return $this->render('admin/coachdevelopment/caas.html.twig', [
            'caas' => $caas
        ]);
    }
}
