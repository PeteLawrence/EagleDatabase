<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ActivityType;
use AppBundle\Form\ActivityTypeType;

/**
 * ActivityType controller.
 *
 * @Route("/activitytype")
 */
class ActivityTypeController extends Controller
{
    /**
     * Lists all ActivityType entities.
     *
     * @Route("/", name="admin_activitytype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $activityTypes = $em->getRepository('AppBundle:ActivityType')->findAll();

        return $this->render('admin/activitytype/index.html.twig', array(
            'activityTypes' => $activityTypes,
        ));
    }

    /**
     * Creates a new ActivityType entity.
     *
     * @Route("/new", name="admin_activitytype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $activityType = new ActivityType();
        $form = $this->createForm('AppBundle\Form\ActivityTypeType', $activityType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activityType);
            $em->flush();

            return $this->redirectToRoute('admin_activitytype_show', array('id' => $activityType->getId()));
        }

        return $this->render('admin/activitytype/new.html.twig', array(
            'activityType' => $activityType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ActivityType entity.
     *
     * @Route("/{id}", name="admin_activitytype_show")
     * @Method("GET")
     */
    public function showAction(ActivityType $activityType)
    {
        $deleteForm = $this->createDeleteForm($activityType);

        return $this->render('admin/activitytype/show.html.twig', array(
            'activityType' => $activityType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ActivityType entity.
     *
     * @Route("/{id}/edit", name="admin_activitytype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ActivityType $activityType)
    {
        $deleteForm = $this->createDeleteForm($activityType);
        $editForm = $this->createForm('AppBundle\Form\ActivityTypeType', $activityType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activityType);
            $em->flush();

            return $this->redirectToRoute('admin_activitytype_edit', array('id' => $activityType->getId()));
        }

        return $this->render('admin/activitytype/edit.html.twig', array(
            'activityType' => $activityType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ActivityType entity.
     *
     * @Route("/{id}", name="admin_activitytype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ActivityType $activityType)
    {
        $form = $this->createDeleteForm($activityType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($activityType);
            $em->flush();
        }

        return $this->redirectToRoute('admin_activitytype_index');
    }

    /**
     * Creates a form to delete a ActivityType entity.
     *
     * @param ActivityType $activityType The ActivityType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ActivityType $activityType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_activitytype_delete', array('id' => $activityType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
