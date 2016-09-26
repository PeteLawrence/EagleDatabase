<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Activity;

/**
 * @Route("/activity")
 */
class ActivityController extends Controller
{
    /**
     * @Route("/", name="activity_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $activities = $em->getRepository('AppBundle:Activity')->findAll();

        return $this->render('activity/index.html.twig', array(
            'activities' => $activities,
        ));
    }


    /**
     * @Route("/{id}", name="activity_view")
     */
    public function viewAction(Request $request, Activity $activity)
    {

        // replace this example code with whatever you need
        return $this->render(
            'activity/view.html.twig',
            [
                'activity' => $activity,
                'google_maps_key' => $this->getParameter('site.google_maps_key')
            ]
        );
    }
}
