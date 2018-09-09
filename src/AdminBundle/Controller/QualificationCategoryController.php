<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\QualificationCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Qualificationcategory controller.
 *
 * @Route("/qualificationcategory")
 */
class QualificationCategoryController extends Controller
{
    /**
     * Lists all qualificationCategory entities.
     *
     * @Route("/", name="admin_qualificationcategory_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qualificationCategories = $em->getRepository('AppBundle:QualificationCategory')->findAll();

        return $this->render('admin/qualificationcategory/index.html.twig', array(
            'qualificationCategories' => $qualificationCategories,
        ));
    }

    /**
     * Creates a new qualificationCategory entity.
     *
     * @Route("/new", name="admin_qualificationcategory_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $qualificationCategory = new Qualificationcategory();
        $form = $this->createForm('AppBundle\Form\Type\QualificationCategoryType', $qualificationCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qualificationCategory);
            $em->flush();

            return $this->redirectToRoute('admin_qualificationcategory_show', array('id' => $qualificationCategory->getId()));
        }

        return $this->render('admin/qualificationcategory/new.html.twig', array(
            'qualificationCategory' => $qualificationCategory,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a qualificationCategory entity.
     *
     * @Route("/{id}", name="admin_qualificationcategory_show", methods={"GET"})
     */
    public function showAction(QualificationCategory $qualificationCategory)
    {
        $deleteForm = $this->createDeleteForm($qualificationCategory);

        return $this->render('admin/qualificationcategory/show.html.twig', array(
            'qualificationCategory' => $qualificationCategory,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing qualificationCategory entity.
     *
     * @Route("/{id}/edit", name="admin_qualificationcategory_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, QualificationCategory $qualificationCategory)
    {
        $deleteForm = $this->createDeleteForm($qualificationCategory);
        $editForm = $this->createForm('AppBundle\Form\Type\QualificationCategoryType', $qualificationCategory);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_qualificationcategory_edit', array('id' => $qualificationCategory->getId()));
        }

        return $this->render('admin/qualificationcategory/edit.html.twig', array(
            'qualificationCategory' => $qualificationCategory,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a qualificationCategory entity.
     *
     * @Route("/{id}", name="admin_qualificationcategory_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, QualificationCategory $qualificationCategory)
    {
        $form = $this->createDeleteForm($qualificationCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($qualificationCategory);
            $em->flush();
        }

        return $this->redirectToRoute('admin_qualificationcategory_index');
    }

    /**
     * Creates a form to delete a qualificationCategory entity.
     *
     * @param QualificationCategory $qualificationCategory The qualificationCategory entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(QualificationCategory $qualificationCategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_qualificationcategory_delete', array('id' => $qualificationCategory->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
