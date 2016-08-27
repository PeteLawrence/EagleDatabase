<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ParticipantRole;
use AppBundle\Form\ParticipantRoleType;

/**
 * ParticipantRole controller.
 *
 * @Route("/participantrole")
 */
class ParticipantRoleController extends Controller
{
    /**
     * Lists all ParticipantRole entities.
     *
     * @Route("/", name="admin_participantrole_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $participantRoles = $em->getRepository('AppBundle:ParticipantRole')->findAll();

        return $this->render('admin/participantrole/index.html.twig', array(
            'participantRoles' => $participantRoles,
        ));
    }

    /**
     * Creates a new ParticipantRole entity.
     *
     * @Route("/new", name="admin_participantrole_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $participantRole = new ParticipantRole();
        $form = $this->createForm('AppBundle\Form\ParticipantRoleType', $participantRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participantRole);
            $em->flush();

            return $this->redirectToRoute('admin_participantrole_index');
        }

        return $this->render('admin/participantrole/new.html.twig', array(
            'participantRole' => $participantRole,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ParticipantRole entity.
     *
     * @Route("/{id}", name="admin_participantrole_show")
     * @Method("GET")
     */
    public function showAction(ParticipantRole $participantRole)
    {
        $deleteForm = $this->createDeleteForm($participantRole);

        return $this->render('admin/participantrole/show.html.twig', array(
            'participantRole' => $participantRole,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ParticipantRole entity.
     *
     * @Route("/{id}/edit", name="admin_participantrole_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ParticipantRole $participantRole)
    {
        $deleteForm = $this->createDeleteForm($participantRole);
        $editForm = $this->createForm('AppBundle\Form\ParticipantRoleType', $participantRole);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participantRole);
            $em->flush();

            return $this->redirectToRoute('admin_participantrole_index');
        }

        return $this->render('admin/participantrole/edit.html.twig', array(
            'participantRole' => $participantRole,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ParticipantRole entity.
     *
     * @Route("/{id}", name="admin_participantrole_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ParticipantRole $participantRole)
    {
        $form = $this->createDeleteForm($participantRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participantRole);
            $em->flush();
        }

        return $this->redirectToRoute('admin_participantrole_index');
    }

    /**
     * Creates a form to delete a ParticipantRole entity.
     *
     * @param ParticipantRole $participantRole The ParticipantRole entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ParticipantRole $participantRole)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_participantrole_delete', array('id' => $participantRole->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
