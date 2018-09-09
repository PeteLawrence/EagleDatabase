<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\MemberRegistration;
use AppBundle\Entity\MemberRegistrationCharge;

/**
 * MemberRegistration controller.
 *
 * @Route("/memberregistration")
 */
class MemberRegistrationController extends Controller
{
    /**
     * Lists all MemberRegistration entities.
     *
     * @Route("/", name="admin_memberregistration_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $memberRegistrations = $em->getRepository('AppBundle:MemberRegistration')->findAll();

        return $this->render('admin/memberregistration/index.html.twig', array(
            'memberRegistrations' => $memberRegistrations,
        ));
    }

    /**
     * Creates a new MemberRegistration entity.
     *
     * @Route("/new", name="admin_memberregistration_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $memberRegistration = new MemberRegistration();
        $form = $this->createForm('AppBundle\Form\Type\MemberRegistrationType', $memberRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Populate the MemberRegistration object
            $memberRegistration->setRegistrationDateTime(new \DateTime());

            // Create a MemberRegistrationCharge object
            $memberRegistrationCharge = new MemberRegistrationCharge;
            $memberRegistrationCharge->setPerson($memberRegistration->getPerson());
            $memberRegistrationCharge->setDescription('Membership');
            $memberRegistrationCharge->setAmount($memberRegistration->getMembershipTypePeriod()->getPrice());
            $memberRegistrationCharge->setPaid(false);
            $memberRegistrationCharge->setCreateddatetime(new \DateTime());
            $memberRegistrationCharge->setDuedatetime(new \DateTime());
            $memberRegistrationCharge->setMemberRegistration($memberRegistration);


            $em = $this->getDoctrine()->getManager();
            $em->persist($memberRegistration);
            $em->persist($memberRegistrationCharge);
            $em->flush();

            return $this->redirectToRoute('admin_memberregistration_show', array('id' => $memberRegistration->getId()));
        }

        return $this->render('admin/memberregistration/new.html.twig', array(
            'memberRegistration' => $memberRegistration,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a MemberRegistration entity.
     *
     * @Route("/{id}", name="admin_memberregistration_show", methods={"GET"})
     */
    public function showAction(MemberRegistration $memberRegistration)
    {
        $deleteForm = $this->createDeleteForm($memberRegistration);

        return $this->render('admin/memberregistration/show.html.twig', array(
            'memberRegistration' => $memberRegistration,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MemberRegistration entity.
     *
     * @Route("/{id}/edit", name="admin_memberregistration_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, MemberRegistration $memberRegistration)
    {
        $deleteForm = $this->createDeleteForm($memberRegistration);
        $editForm = $this->createForm('AppBundle\Form\Type\MemberRegistrationType', $memberRegistration);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($memberRegistration);
            $em->flush();

            return $this->redirectToRoute('admin_memberregistration_edit', array('id' => $memberRegistration->getId()));
        }

        return $this->render('admin/memberregistration/edit.html.twig', array(
            'memberRegistration' => $memberRegistration,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a MemberRegistration entity.
     *
     * @Route("/{id}", name="admin_memberregistration_delete"), methods={"DELETE"}
     */
    public function deleteAction(Request $request, MemberRegistration $memberRegistration)
    {
        $form = $this->createDeleteForm($memberRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($memberRegistration);
            $em->flush();
        }

        return $this->redirectToRoute('admin_memberregistration_index');
    }

    /**
     * Creates a form to delete a MemberRegistration entity.
     *
     * @param MemberRegistration $memberRegistration The MemberRegistration entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MemberRegistration $memberRegistration)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_memberregistration_delete', array('id' => $memberRegistration->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
