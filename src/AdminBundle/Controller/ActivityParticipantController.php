<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Participant;
use AppBundle\Form\ActivityType;

/**
 * Activity controller.
 *
 * @Route("/activity/{id}/participant")
 */
class ActivityParticipantController extends Controller
{
    /**
     * Gets a list of Activity participants.
     *
     * @Route("/", name="activity_participant_list")
     * @Method("GET")
     */
    public function indexAction(Request $request, Activity $activity)
    {
        return $this->render('admin/activity/participants.html.twig', array(
            'activity' => $activity
        ));
    }


    /**
     * Creates a new Activity entity.
     *
     * @Route("/new", name="activity_participant_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Activity $activity)
    {
        $participant = new Participant();

        $form = $this->createForm('AppBundle\Form\ParticipantType', $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('activity_show', array('id' => $activity->getId()));
        }

        return $this->render('admin/activity/participant_new.html.twig', array(
            'activity' => $activity,
            'form' => $form->createView(),
        ));
    }


    /**
     * Gets a list of Activity participants.
     *
     * @Route("/{pid}/delete", name="activity_participant_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, Participant $participant)
    {
        $deleteForm = $this->createDeleteForm($participant);

        if ($request->getMethod() == 'DELETE') {
            return $this->redirectToRoute('activity_participant_list', [ 'id' => $participant->getActivity()->getId() ]);
        }

        return $this->render('admin/activity/participant_delete.html.twig', array(
            'participant' => $participant,
            'delete_form' => $deleteForm->createView()
        ));
    }


    private function createDeleteForm(Participant $participant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activity_participant_delete', array('id' => $participant->getActivity()->getId(), 'pid' => $participant->getId() )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
