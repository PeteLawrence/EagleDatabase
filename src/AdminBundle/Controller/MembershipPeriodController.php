<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\MembershipPeriod;

/**
 * MembershipPeriod controller.
 *
 * @Route("/membershipperiod")
 */
class MembershipPeriodController extends Controller
{
    /**
     * Lists all MembershipPeriod entities.
     *
     * @Route("/", name="admin_membershipperiod_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $membershipPeriods = $em->getRepository('AppBundle:MembershipPeriod')->findAll();

        return $this->render('admin/membershipperiod/index.html.twig', array(
            'membershipPeriods' => $membershipPeriods,
        ));
    }

    /**
     * Creates a new MembershipPeriod entity.
     *
     * @Route("/new", name="admin_membershipperiod_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $membershipPeriod = new MembershipPeriod();
        $form = $this->createForm('AppBundle\Form\Type\MembershipPeriodType', $membershipPeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipPeriod);
            $em->flush();

            return $this->redirectToRoute('admin_membershipperiod_show', array('id' => $membershipPeriod->getId()));
        }

        return $this->render('admin/membershipperiod/new.html.twig', array(
            'membershipPeriod' => $membershipPeriod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MembershipPeriod entity.
     *
     * @Route("/{id}", name="admin_membershipperiod_show")
     * @Method("GET")
     */
    public function showAction(MembershipPeriod $membershipPeriod)
    {
        $deleteForm = $this->createDeleteForm($membershipPeriod);

        return $this->render('admin/membershipperiod/show.html.twig', array(
            'membershipPeriod' => $membershipPeriod,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MembershipPeriod entity.
     *
     * @Route("/{id}/edit", name="admin_membershipperiod_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, MembershipPeriod $membershipPeriod)
    {
        $deleteForm = $this->createDeleteForm($membershipPeriod);
        $editForm = $this->createForm('AppBundle\Form\Type\MembershipPeriodType', $membershipPeriod);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipPeriod);
            $em->flush();

            return $this->redirectToRoute('admin_membershipperiod_edit', array('id' => $membershipPeriod->getId()));
        }

        return $this->render('admin/membershipperiod/edit.html.twig', array(
            'membershipPeriod' => $membershipPeriod,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MembershipPeriod entity.
     *
     * @Route("/{id}", name="admin_membershipperiod_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, MembershipPeriod $membershipPeriod)
    {
        $form = $this->createDeleteForm($membershipPeriod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membershipPeriod);
            $em->flush();
        }

        return $this->redirectToRoute('admin_membershipperiod_index');
    }

    /**
     * Creates a form to delete a MembershipPeriod entity.
     *
     * @param MembershipPeriod $membershipPeriod The MembershipPeriod entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MembershipPeriod $membershipPeriod)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_membershipperiod_delete', array('id' => $membershipPeriod->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
