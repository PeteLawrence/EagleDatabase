<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MembershipType;
use AppBundle\Form\MembershipTypeType;

/**
 * MembershipType controller.
 *
 * @Route("/membershiptype")
 */
class MembershipTypeController extends Controller
{
    /**
     * Lists all MembershipType entities.
     *
     * @Route("/", name="membershiptype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $membershipTypes = $em->getRepository('AppBundle:MembershipType')->findAll();

        return $this->render('membershiptype/index.html.twig', array(
            'membershipTypes' => $membershipTypes,
        ));
    }

    /**
     * Creates a new MembershipType entity.
     *
     * @Route("/new", name="membershiptype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $membershipType = new MembershipType();
        $form = $this->createForm('AppBundle\Form\MembershipTypeType', $membershipType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipType);
            $em->flush();

            return $this->redirectToRoute('membershiptype_show', array('id' => $membershipType->getId()));
        }

        return $this->render('membershiptype/new.html.twig', array(
            'membershipType' => $membershipType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MembershipType entity.
     *
     * @Route("/{id}", name="membershiptype_show")
     * @Method("GET")
     */
    public function showAction(MembershipType $membershipType)
    {
        $deleteForm = $this->createDeleteForm($membershipType);

        return $this->render('membershiptype/show.html.twig', array(
            'membershipType' => $membershipType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MembershipType entity.
     *
     * @Route("/{id}/edit", name="membershiptype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MembershipType $membershipType)
    {
        $deleteForm = $this->createDeleteForm($membershipType);
        $editForm = $this->createForm('AppBundle\Form\MembershipTypeType', $membershipType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipType);
            $em->flush();

            return $this->redirectToRoute('membershiptype_edit', array('id' => $membershipType->getId()));
        }

        return $this->render('membershiptype/edit.html.twig', array(
            'membershipType' => $membershipType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MembershipType entity.
     *
     * @Route("/{id}", name="membershiptype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MembershipType $membershipType)
    {
        $form = $this->createDeleteForm($membershipType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membershipType);
            $em->flush();
        }

        return $this->redirectToRoute('membershiptype_index');
    }

    /**
     * Creates a form to delete a MembershipType entity.
     *
     * @param MembershipType $membershipType The MembershipType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MembershipType $membershipType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('membershiptype_delete', array('id' => $membershipType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
