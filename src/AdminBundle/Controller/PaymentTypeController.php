<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Paymenttype controller.
 *
 * @Route("/paymenttype")
 */
class PaymentTypeController extends Controller
{
    /**
     * Lists all paymentType entities.
     *
     * @Route("/", name="admin_paymenttype_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $paymentTypes = $em->getRepository('AppBundle:PaymentType')->findAll();

        return $this->render('admin/paymenttype/index.html.twig', array(
            'paymentTypes' => $paymentTypes,
        ));
    }

    /**
     * Creates a new paymentType entity.
     *
     * @Route("/new", name="admin_paymenttype_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $paymentType = new Paymenttype();
        $form = $this->createForm('AppBundle\Form\PaymentTypeType', $paymentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paymentType);
            $em->flush($paymentType);

            return $this->redirectToRoute('admin_paymenttype_index');
        }

        return $this->render('admin/paymenttype/new.html.twig', array(
            'paymentType' => $paymentType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing paymentType entity.
     *
     * @Route("/{id}/edit", name="admin_paymenttype_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, PaymentType $paymentType)
    {
        $deleteForm = $this->createDeleteForm($paymentType);
        $editForm = $this->createForm('AppBundle\Form\PaymentTypeType', $paymentType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_paymenttype_index');
        }

        return $this->render('admin/paymenttype/edit.html.twig', array(
            'paymentType' => $paymentType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a paymentType entity.
     *
     * @Route("/{id}", name="admin_paymenttype_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, PaymentType $paymentType)
    {
        $form = $this->createDeleteForm($paymentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paymentType);
            $em->flush($paymentType);
        }

        return $this->redirectToRoute('admin_paymenttype_index');
    }

    /**
     * Creates a form to delete a paymentType entity.
     *
     * @param PaymentType $paymentType The paymentType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PaymentType $paymentType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_paymenttype_delete', array('id' => $paymentType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
