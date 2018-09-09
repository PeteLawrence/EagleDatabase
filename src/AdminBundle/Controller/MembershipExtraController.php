<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\MembershipExtra;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Membershipextra controller.
 *
 * @Route("/membershipextra")
 */
class MembershipExtraController extends Controller
{
    /**
     * Lists all membershipExtra entities.
     *
     * @Route("/", name="admin_membershipextra_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $membershipExtras = $em->getRepository('AppBundle:MembershipExtra')->findAll();

        return $this->render('admin/membershipextra/index.html.twig', array(
            'membershipExtras' => $membershipExtras,
        ));
    }

    /**
     * Creates a new membershipExtra entity.
     *
     * @Route("/new", name="admin_membershipextra_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $membershipExtra = new Membershipextra();
        $form = $this->createForm('AppBundle\Form\Type\MembershipExtraType', $membershipExtra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipExtra);
            $em->flush($membershipExtra);

            return $this->redirectToRoute('admin_membershipextra_show', array('id' => $membershipExtra->getId()));
        }

        return $this->render('admin/membershipextra/new.html.twig', array(
            'membershipExtra' => $membershipExtra,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a membershipExtra entity.
     *
     * @Route("/{id}", name="admin_membershipextra_show", methods={"GET"})
     */
    public function showAction(MembershipExtra $membershipExtra)
    {
        $deleteForm = $this->createDeleteForm($membershipExtra);

        return $this->render('admin/membershipextra/show.html.twig', array(
            'membershipExtra' => $membershipExtra,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing membershipExtra entity.
     *
     * @Route("/{id}/edit", name="admin_membershipextra_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, MembershipExtra $membershipExtra)
    {
        $deleteForm = $this->createDeleteForm($membershipExtra);
        $editForm = $this->createForm('AppBundle\Form\Type\MembershipExtraType', $membershipExtra);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_membershipextra_index');
        }

        return $this->render('admin/membershipextra/edit.html.twig', array(
            'membershipExtra' => $membershipExtra,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a membershipExtra entity.
     *
     * @Route("/{id}", name="admin_membershipextra_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, MembershipExtra $membershipExtra)
    {
        $form = $this->createDeleteForm($membershipExtra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membershipExtra);
            $em->flush($membershipExtra);
        }

        return $this->redirectToRoute('admin_membershipextra_index');
    }

    /**
     * Creates a form to delete a membershipExtra entity.
     *
     * @param MembershipExtra $membershipExtra The membershipExtra entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MembershipExtra $membershipExtra)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_membershipextra_delete', array('id' => $membershipExtra->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
