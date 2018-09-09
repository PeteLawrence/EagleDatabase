<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\MemberQualification;

/**
 * MemberQualification controller.
 *
 * @Route("/person/{personId}/qualification")
 */
class MemberQualificationController extends Controller
{
    /**
     * Lists all MemberQualification entities.
     *
     * @Route("/", name="admin_memberqualification_index", methods={"GET"})
     */
    public function indexAction(Request $request, $personId)
    {
        $em = $this->getDoctrine()->getManager();

        $person = $em->getRepository('AppBundle:Person')->findOneById($personId);
        $memberQualifications = $em->getRepository('AppBundle:MemberQualification')->findAll();

        return $this->render('admin/memberqualification/index.html.twig', array(
            'person' => $person,
            'memberQualifications' => $memberQualifications,
        ));
    }

    /**
     * Creates a new MemberQualification entity.
     *
     * @Route("/new", name="admin_memberqualification_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, $personId)
    {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('AppBundle:Person')->findOneById($personId);

        $memberQualification = new MemberQualification();
        $form = $this->createForm('AppBundle\Form\Type\MemberQualificationType', $memberQualification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $memberQualification->setPerson($person);
            $em->persist($memberQualification);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', array('id' => $person->getId()));
        }

        return $this->render('admin/memberqualification/new.html.twig', array(
            'memberQualification' => $memberQualification,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing MemberQualification entity.
     *
     * @Route("/{id}/edit", name="admin_memberqualification_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, MemberQualification $memberQualification)
    {
        $deleteForm = $this->createDeleteForm($memberQualification);
        $editForm = $this->createForm('AppBundle\Form\Type\MemberQualificationType', $memberQualification);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($memberQualification);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', array('id' => $memberQualification->getPerson()->getId()));
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
     * @Route("/{id}", name="admin_memberqualification_delete", methods={"DELETE"})
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

        return $this->redirectToRoute('admin_person_edit', array('id' => $memberQualification->getPerson()->getId()));
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
            ->setAction($this->generateUrl('admin_memberqualification_delete', array('personId' => $memberQualification->getPerson()->getId(), 'id' => $memberQualification->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
