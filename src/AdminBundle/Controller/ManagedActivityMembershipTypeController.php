<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\ManagedActivityMembershipType;
use AppBundle\Entity\ManagedActivity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Managedactivitymembershiptype controller.
 *
 * @Route("/managedactivity")
 */
class ManagedActivityMembershipTypeController extends Controller
{
    /**
     * Lists all managedActivityMembershipType entities.
     *
     * @Route("/{id}/membershiptype", name="admin_managedactivitymembershiptype_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $managedActivityMembershipTypes = $em->getRepository('AppBundle:ManagedActivityMembershipType')->findAll();

        return $this->render('admin/managedactivitymembershiptype/index.html.twig', array(
            'managedActivityMembershipTypes' => $managedActivityMembershipTypes,
        ));
    }

    /**
     * Creates a new managedActivityMembershipType entity.
     *
     * @Route("/{id}/membershiptype/new", name="admin_managedactivitymembershiptype_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, ManagedActivity $managedActivity)
    {
        $managedActivityMembershipType = new Managedactivitymembershiptype();

        $managedActivityMembershipType->setManagedActivity($managedActivity);


        $form = $this->createForm('AppBundle\Form\Type\ManagedActivityMembershipTypeType', $managedActivityMembershipType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($managedActivityMembershipType);
            $em->flush($managedActivityMembershipType);

            return $this->redirectToRoute('admin_activity_edit', [ 'id' => $managedActivity->getId() ]);
        }

        return $this->render('admin/managedactivitymembershiptype/new.html.twig', array(
            'managedActivityMembershipType' => $managedActivityMembershipType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a managedActivityMembershipType entity.
     *
     * @Route("/{managedActivityId}/membershiptype/{id}", name="admin_managedactivitymembershiptype_show", methods={"GET"})
     */
    public function showAction(ManagedActivityMembershipType $managedActivityMembershipType)
    {
        $deleteForm = $this->createDeleteForm($managedActivityMembershipType);

        return $this->render('admin/managedactivitymembershiptype/show.html.twig', array(
            'managedActivityMembershipType' => $managedActivityMembershipType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing managedActivityMembershipType entity.
     *
     * @Route("/{managedActivityId}/membershiptype/{id}/edit", name="admin_managedactivitymembershiptype_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, ManagedActivityMembershipType $managedActivityMembershipType, $managedActivityId)
    {
        $deleteForm = $this->createDeleteForm($managedActivityMembershipType);
        $editForm = $this->createForm('AppBundle\Form\Type\ManagedActivityMembershipTypeType', $managedActivityMembershipType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_activity_edit', [ 'id' => $managedActivityId ]);
        }

        return $this->render('admin/managedactivitymembershiptype/edit.html.twig', array(
            'managedActivityMembershipType' => $managedActivityMembershipType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a managedActivityMembershipType entity.
     *
     * @Route("/{managedActivityId}/membershiptype/{id}/delete", name="admin_managedactivitymembershiptype_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, ManagedActivityMembershipType $managedActivityMembershipType, $managedActivityId)
    {
        $form = $this->createDeleteForm($managedActivityMembershipType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($managedActivityMembershipType);
            $em->flush($managedActivityMembershipType);
        }

        return $this->redirectToRoute('admin_activity_edit', [ 'id' => $managedActivityId ]);
    }

    /**
     * Creates a form to delete a managedActivityMembershipType entity.
     *
     * @param ManagedActivityMembershipType $managedActivityMembershipType The managedActivityMembershipType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ManagedActivityMembershipType $managedActivityMembershipType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_managedactivitymembershiptype_delete', array('managedActivityId' => $managedActivityMembershipType->getManagedActivity()->getId(), 'id' => $managedActivityMembershipType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
