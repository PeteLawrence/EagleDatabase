<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MemberQualification;
use AppBundle\Form\MemberQualificationType;

/**
 * MemberQualification controller.
 *
 * @Route("/memberqualification")
 */
class MemberQualificationController extends Controller
{
    /**
     * Lists all MemberQualification entities.
     *
     * @Route("/", name="admin_memberqualification_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $memberQualifications = $em->getRepository('AppBundle:MemberQualification')->findAll();

        return $this->render('admin/memberqualification/index.html.twig', array(
            'memberQualifications' => $memberQualifications,
        ));
    }

    /**
     * Creates a new MemberQualification entity.
     *
     * @Route("/new", name="admin_memberqualification_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $memberQualification = new MemberQualification();
        $form = $this->createForm('AppBundle\Form\MemberQualificationType', $memberQualification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($memberQualification);
            $em->flush();

            return $this->redirectToRoute('admin_memberqualification_show', array('id' => $memberQualification->getId()));
        }

        return $this->render('admin/memberqualification/new.html.twig', array(
            'memberQualification' => $memberQualification,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MemberQualification entity.
     *
     * @Route("/{id}", name="admin_memberqualification_show")
     * @Method("GET")
     */
    public function showAction(MemberQualification $memberQualification)
    {
        $deleteForm = $this->createDeleteForm($memberQualification);

        return $this->render('admin/memberqualification/show.html.twig', array(
            'memberQualification' => $memberQualification,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MemberQualification entity.
     *
     * @Route("/{id}/edit", name="admin_memberqualification_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MemberQualification $memberQualification)
    {
        $deleteForm = $this->createDeleteForm($memberQualification);
        $editForm = $this->createForm('AppBundle\Form\MemberQualificationType', $memberQualification);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($memberQualification);
            $em->flush();

            return $this->redirectToRoute('admin_memberqualification_edit', array('id' => $memberQualification->getId()));
        }

        return $this->render('admin/memberqualification/edit.html.twig', array(
            'memberQualification' => $memberQualification,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MemberQualification entity.
     *
     * @Route("/{id}", name="admin_memberqualification_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MemberQualification $memberQualification)
    {
        $form = $this->createDeleteForm($memberQualification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($memberQualification);
            $em->flush();
        }

        return $this->redirectToRoute('admin_memberqualification_index');
    }

    /**
     * Creates a form to delete a MemberQualification entity.
     *
     * @param MemberQualification $memberQualification The MemberQualification entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MemberQualification $memberQualification)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_memberqualification_delete', array('id' => $memberQualification->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
