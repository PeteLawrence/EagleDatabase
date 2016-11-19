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


    /**
     * @Route("/{id}/signup", name="activity_signup")
     */
    public function signupAction(Request $request, Activity $activity)
    {
        $em = $this->get('doctrine')->getManager();

        $signupForm = $this->buildSignupForm($activity);
        $signupForm->handleRequest($request);

        if ($signupForm->isSubmitted() && $signupForm->isValid()) {
            $participantStatus = $em->getRepository('AppBundle:ParticipantStatus')->findOneByStatus('Attending');

            $participant = new \AppBundle\Entity\Participant;
            $participant->setManagedActivity($activity);
            $participant->setPerson($this->get('security.token_storage')->getToken()->getUser());
            $participant->setSignupDateTime(new \DateTime());
            $participant->setParticipantStatus($participantStatus);

            $em->persist($participant);
            $em->flush();

            $this->addFlash('notice', 'You have signed up to the activity');

            return $this->redirectToRoute('activity_view', [ 'id' => $activity->getId() ]);
        }

        // replace this example code with whatever you need
        return $this->render(
            'activity/signup.html.twig',
            [
                'activity' => $activity,
                'form' => $signupForm->createView()
            ]
        );
    }


    private function buildSignupForm($activity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activity_signup', array('id' => $activity->getId())))
            ->setMethod('POST')
            ->getForm()
        ;
    }
}
