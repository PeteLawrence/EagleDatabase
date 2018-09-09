<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\MembershipTypePeriodExtra;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Membershiptypeperiodextra controller.
 *
 * @Route("membershiptypeperiodextra")
 */
class MembershipTypePeriodExtraController extends Controller
{
    /**
     * Lists all membershipTypePeriodExtra entities.
     *
     * @Route("/", name="admin_membershiptypeperiodextra_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $membershipTypePeriodExtras = $em->getRepository('AppBundle:MembershipTypePeriodExtra')->findAll();

        return $this->render('admin/membershiptypeperiodextra/index.html.twig', array(
            'membershipTypePeriodExtras' => $membershipTypePeriodExtras,
        ));
    }

    /**
     * Creates a new membershipTypePeriodExtra entity.
     *
     * @Route("/new", name="admin_membershiptypeperiodextra_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $membershipTypePeriodExtra = new Membershiptypeperiodextra();
        $form = $this->createForm('AppBundle\Form\Type\MembershipTypePeriodExtraType', $membershipTypePeriodExtra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($membershipTypePeriodExtra);
            $em->flush($membershipTypePeriodExtra);

            return $this->redirectToRoute('admin_membershiptypeperiodextra_show', array('id' => $membershipTypePeriodExtra->getId()));
        }

        return $this->render('admin/membershiptypeperiodextra/new.html.twig', array(
            'membershipTypePeriodExtra' => $membershipTypePeriodExtra,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a membershipTypePeriodExtra entity.
     *
     * @Route("/{id}", name="admin_membershiptypeperiodextra_show", methods={"GET"})
     */
    public function showAction(MembershipTypePeriodExtra $membershipTypePeriodExtra)
    {
        $deleteForm = $this->createDeleteForm($membershipTypePeriodExtra);

        return $this->render('admin/membershiptypeperiodextra/show.html.twig', array(
            'membershipTypePeriodExtra' => $membershipTypePeriodExtra,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing membershipTypePeriodExtra entity.
     *
     * @Route("/{id}/edit", name="admin_membershiptypeperiodextra_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, MembershipTypePeriodExtra $membershipTypePeriodExtra)
    {
        $deleteForm = $this->createDeleteForm($membershipTypePeriodExtra);
        $editForm = $this->createForm('AppBundle\Form\Type\MembershipTypePeriodExtraType', $membershipTypePeriodExtra);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_membershiptypeperiodextra_edit', array('id' => $membershipTypePeriodExtra->getId()));
        }

        return $this->render('admin/membershiptypeperiodextra/edit.html.twig', array(
            'membershipTypePeriodExtra' => $membershipTypePeriodExtra,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a membershipTypePeriodExtra entity.
     *
     * @Route("/{id}", name="admin_membershiptypeperiodextra_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, MembershipTypePeriodExtra $membershipTypePeriodExtra)
    {
        $form = $this->createDeleteForm($membershipTypePeriodExtra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membershipTypePeriodExtra);
            $em->flush($membershipTypePeriodExtra);
        }

        return $this->redirectToRoute('admin_membershiptypeperiodextra_index');
    }

    /**
     * Creates a form to delete a membershipTypePeriodExtra entity.
     *
     * @param MembershipTypePeriodExtra $membershipTypePeriodExtra The membershipTypePeriodExtra entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(MembershipTypePeriodExtra $membershipTypePeriodExtra)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_membershiptypeperiodextra_delete', array('id' => $membershipTypePeriodExtra->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
