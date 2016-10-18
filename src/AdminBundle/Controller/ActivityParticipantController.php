<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Participant;

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
        $participant->setManagedActivity($activity);

        $form = $this->createForm('AppBundle\Form\Type\ParticipantType', $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

            return $this->redirectToRoute('activity_participant_list', array('id' => $activity->getId()));
        }

        return $this->render('admin/activity/participant_new.html.twig', array(
            'activity' => $activity,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Activity entity.
     *
     * @Route("/{pid}", name="activity_partcipant_show")
     * @Method("GET")
     */
    public function showAction(Request $request, $pid)
    {
        $em = $this->getDoctrine()->getManager();
        $participant = $em->getRepository('AppBundle:Participant')->findOneById($pid);

        $deleteForm = $this->createDeleteForm($participant);

        return $this->render('admin/participant/show.html.twig', array(
            'activity' => $participant,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Activity entity.
     *
     * @Route("/{pid}/edit", name="activity_participant_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $pid)
    {
        $em = $this->getDoctrine()->getManager();
        $participant = $em->getRepository('AppBundle:Participant')->findOneById($pid);

        $deleteForm = $this->createDeleteForm($participant);
        $editForm = $this->createForm('AppBundle\Form\Type\Participant', $participant);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

            return $this->redirectToRoute('activity_participant_list', array('id' => $participant->getActivity()->getId(), $participant->getId()));
        }

        return $this->render('admin/participant/edit.html.twig', array(
            'participant' => $participant,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Gets a list of Activity participants.
     *
     * @Route("/{pid}/delete", name="activity_participant_delete")
     * @Method({"GET", "DELETE"})
     */
    public function deleteAction(Request $request, $pid)
    {
        $em = $this->getDoctrine()->getManager();
        $participant = $em->getRepository('AppBundle:Participant')->findOneById($pid);


        $deleteForm = $this->createDeleteForm($participant);

        if ($request->getMethod() == 'DELETE') {
            $em->remove($participant);
            $em->flush();

            return $this->redirectToRoute('activity_participant_list', [ 'id' => $participant->getManagedActivity()->getId() ]);
        }

        return $this->render('admin/activity/participant_delete.html.twig', array(
            'participant' => $participant,
            'delete_form' => $deleteForm->createView()
        ));
    }


    private function createDeleteForm(Participant $participant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activity_participant_delete', array('id' => $participant->getManagedActivity()->getId(), 'pid' => $participant->getId() )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
