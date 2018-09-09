<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\MemberRegistration;
use AppBundle\Entity\MemberRegistrationCharge;
use AppBundle\Entity\Charge;
use AppBundle\Entity\OtherCharge;

/**
 * MemberRegistration controller.
 *
 * @Route("/person/{personId}/charge")
 */
class MemberChargeController extends Controller
{
    /**
     * Creates a new MemberCharge entity.
     *
     * @Route("/new", name="admin_membercharge_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, $personId)
    {
        $em = $this->getDoctrine()->getManager();

        $person = $em->getRepository('AppBundle:Person')->findOneById($personId);

        $charge = new OtherCharge();
        $charge->setPerson($person);
        $charge->setDuedatetime(new \DateTime());
        $form = $this->createForm('AppBundle\Form\Type\OtherChargeType', $charge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $charge->setCreateddatetime(new \DateTime());

            $em->persist($charge);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', array('id' => $person->getId()));
        }

        return $this->render('admin/person/chargenew.html.twig', array(
            'charge' => $charge,
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a Charge entity.
     *
     * @Route("/{id}", name="admin_membercharge_show", methods={"GET"})
     */
    public function showAction(Charge $charge)
    {
        return $this->render('admin/person/chargeshow.html.twig', array(
            'charge' => $charge
        ));
    }


    /**
     * Displays a form to edit a Charge
     *
     * @Route("/{id}/edit", name="admin_membercharge_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Charge $charge)
    {
        $editForm = $this->createForm('AppBundle\Form\Type\OtherChargeType', $charge);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($charge);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', array('id' => $charge->getPerson()->getId()));
        }

        return $this->render('admin/person/chargeedit.html.twig', array(
            'charge' => $charge,
            'edit_form' => $editForm->createView()
        ));
    }


    /**
     * Displays a form to approve a Charge
     *
     * @Route("/{id}/approve", name="admin_membercharge_approve", methods={"GET", "POST"})
     */
    public function approveAction(Request $request, Charge $charge)
    {
        $editForm = $this->createForm('AppBundle\Form\Type\ChargeApproveType', $charge);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //Mark as paid
            $charge->setPaid(true);
            $charge->setPaiddatetime(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($charge);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', array('id' => $charge->getPerson()->getId()));
        }

        return $this->render('admin/person/chargeapprove.html.twig', array(
            'charge' => $charge,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a MemberRegistration entity.
     *
     * @Route("/{id}", name="admin_memberregistration_delete", methods={"DELETE"})
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
