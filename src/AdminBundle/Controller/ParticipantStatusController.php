<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ParticipantStatus;

/**
 * ParticipantStatus controller.
 *
 * @Route("participantstatus")
 */
class ParticipantStatusController extends Controller
{
    /**
     * Lists all ParticipantStatus entities.
     *
     * @Route("/", name="admin_participantstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $participantStatuses = $em->getRepository('AppBundle:ParticipantStatus')->findAll();

        return $this->render('admin/participantstatus/index.html.twig', array(
            'participantStatuses' => $participantStatuses,
        ));
    }

    /**
     * Creates a new ParticipantStatus entity.
     *
     * @Route("/new", name="admin_participantstatus_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $participantStatus = new ParticipantStatus();
        $form = $this->createForm('AppBundle\Form\Type\ParticipantStatusType', $participantStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participantStatus);
            $em->flush();

            return $this->redirectToRoute('admin_participantstatus_index', array('id' => $participantStatus->getId()));
        }

        return $this->render('admin/participantstatus/new.html.twig', array(
            'participantStatus' => $participantStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ParticipantStatus entity.
     *
     * @Route("/{id}", name="admin_participantstatus_show")
     * @Method("GET")
     */
    public function showAction(ParticipantStatus $participantStatus)
    {
        $deleteForm = $this->createDeleteForm($participantStatus);

        return $this->render('admin/participantstatus/show.html.twig', array(
            'participantStatus' => $participantStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ParticipantStatus entity.
     *
     * @Route("/{id}/edit", name="admin_participantstatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ParticipantStatus $participantStatus)
    {
        $deleteForm = $this->createDeleteForm($participantStatus);
        $editForm = $this->createForm('AppBundle\Form\Type\ParticipantStatusType', $participantStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participantStatus);
            $em->flush();

            return $this->redirectToRoute('admin_participantstatus_edit', array('id' => $participantStatus->getId()));
        }

        return $this->render('admin/participantstatus/edit.html.twig', array(
            'participantStatus' => $participantStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ParticipantStatus entity.
     *
     * @Route("/{id}", name="admin_participantstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ParticipantStatus $participantStatus)
    {
        $form = $this->createDeleteForm($participantStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participantStatus);
            $em->flush();
        }

        return $this->redirectToRoute('admin_participantstatus_index');
    }

    /**
     * Creates a form to delete a ParticipantStatus entity.
     *
     * @param ParticipantStatus $participantStatus The ParticipantStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ParticipantStatus $participantStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_participantstatus_delete', array('id' => $participantStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
