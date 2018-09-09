<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Attribute;

/**
 * Attribute controller.
 *
 * @Route("/attribute")
 */
class AttributeController extends Controller
{
    /**
     * Lists all Attribute entities.
     *
     * @Route("/", name="admin_attribute_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $attributes = $em->getRepository('AppBundle:Attribute')->findAll();

        return $this->render('admin/attribute/index.html.twig', array(
            'attributes' => $attributes,
        ));
    }


    /**
     * Creates a new Attribute entity.
     *
     * @Route("/new", name="admin_attribute_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $attribute = new Attribute();

        $form = $this->createForm('AppBundle\Form\Type\AttributeType', $attribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($attribute);
            $em->flush();

            return $this->redirectToRoute('admin_attribute_index');
        }

        return $this->render('admin/attribute/new.html.twig', array(
            'attribute' => $attribute,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing Attribute entity.
     *
     * @Route("/{id}/edit", name="admin_attribute_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Attribute $attribute)
    {
        $editForm = $this->createForm('AppBundle\Form\Type\AttributeType', $attribute);
        $deleteForm = $this->createDeleteForm($attribute);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_attribute_edit', array('id' => $attribute->getId()));
        }

        return $this->render('admin/attribute/edit.html.twig', array(
            'attribute' => $attribute,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a ActivityType entity.
     *
     * @Route("/{id}", name="admin_attribute_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Attribute $attribute)
    {
        $form = $this->createDeleteForm($attribute);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($attribute);
            $em->flush();
        }

        return $this->redirectToRoute('admin_attribute_index');
    }


    /**
     * Creates a form to delete a ActivityType entity.
     *
     * @param ActivityType $activityType The ActivityType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Attribute $attribute)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_attribute_delete', array('id' => $attribute->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
