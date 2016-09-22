<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MembershipTypePeriod;

/**
 * MembershipTypePeriod controller.
 *
 * @Route("/membershiptypeperiod")
 */
class MembershipTypePeriodController extends Controller
{
    /**
     * Lists all MembershipTypePeriod entities.
     *
     * @Route("/", name="admin_membershiptypeperiod_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $membershipTypePeriods = $em->getRepository('AppBundle:MembershipTypePeriod')->findAll();

        return $this->render('admin/membershiptypeperiod/index.html.twig', array(
            'membershipTypePeriods' => $membershipTypePeriods,
        ));
    }

    /**
     * Creates a new MembershipTypePeriod entity.
     *
     * @Route("/new", name="admin_membershiptypeperiod_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $membershipTypePeriod = new MembershipTypePeriod();
        $form = $this->createForm('AppBundle\Form\Type\MembershipTypePeriodType', $membershipTypePeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipTypePeriod);
            $em->flush();

            return $this->redirectToRoute('admin_membershiptypeperiod_show', array('id' => $membershipTypePeriod->getId()));
        }

        return $this->render('admin/membershiptypeperiod/new.html.twig', array(
            'membershipTypePeriod' => $membershipTypePeriod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MembershipTypePeriod entity.
     *
     * @Route("/{id}", name="admin_membershiptypeperiod_show")
     * @Method("GET")
     */
    public function showAction(MembershipTypePeriod $membershipTypePeriod)
    {
        $deleteForm = $this->createDeleteForm($membershipTypePeriod);

        return $this->render('admin/membershiptypeperiod/show.html.twig', array(
            'membershipTypePeriod' => $membershipTypePeriod,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MembershipTypePeriod entity.
     *
     * @Route("/{id}/edit", name="admin_membershiptypeperiod_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MembershipTypePeriod $membershipTypePeriod)
    {
        $deleteForm = $this->createDeleteForm($membershipTypePeriod);
        $editForm = $this->createForm('AppBundle\Form\Type\MembershipTypePeriodType', $membershipTypePeriod);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipTypePeriod);
            $em->flush();

            return $this->redirectToRoute('admin_membershiptypeperiod_edit', array('id' => $membershipTypePeriod->getId()));
        }

        return $this->render('admin/membershiptypeperiod/edit.html.twig', array(
            'membershipTypePeriod' => $membershipTypePeriod,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MembershipTypePeriod entity.
     *
     * @Route("/{id}", name="admin_membershiptypeperiod_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MembershipTypePeriod $membershipTypePeriod)
    {
        $form = $this->createDeleteForm($membershipTypePeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membershipTypePeriod);
            $em->flush();
        }

        return $this->redirectToRoute('admin_membershiptypeperiod_index');
    }

    /**
     * Creates a form to delete a MembershipTypePeriod entity.
     *
     * @param MembershipTypePeriod $membershipTypePeriod The MembershipTypePeriod entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MembershipTypePeriod $membershipTypePeriod)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_membershiptypeperiod_delete', array('id' => $membershipTypePeriod->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
