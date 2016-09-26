<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Activity;
use AppBundle\Entity\ManagedActivity;
use AppBundle\Entity\UnmanagedActivity;

/**
 * Activity controller.
 *
 * @Route("/activity")
 */
class ActivityController extends Controller
{
    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="admin_activity_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $activities = $em->getRepository('AppBundle:Activity')->findAll();

        return $this->render('admin/activity/index.html.twig', array(
            'activities' => $activities,
        ));
    }

    /**
     * Creates a new Activity entity.
     *
     * @Route("/new", name="admin_activity_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if ($request->get('type') == 'unmanaged') {
            $activity = new UnmanagedActivity();
            $form = $this->createForm('AppBundle\Form\Type\UnmanagedActivityType', $activity);
        } else {
            $activity = new ManagedActivity();
            $form = $this->createForm('AppBundle\Form\Type\ManagedActivityType', $activity);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($activity instanceof ManagedActivity) {
                //Generate a signin key
                $generator = new \Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator();
                $generator->setLength(16);
                $signInKey = $generator->generatePassword();
                $activity->setSigninKey($signInKey);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('admin_activity_show', array('id' => $activity->getId()));
        }

        return $this->render('admin/activity/new.html.twig', array(
            'activity' => $activity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Activity entity.
     *
     * @Route("/{id}", name="admin_activity_show")
     * @Method("GET")
     */
    public function showAction(Activity $activity)
    {
        $deleteForm = $this->createDeleteForm($activity);

        return $this->render('admin/activity/show.html.twig', array(
            'activity' => $activity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Activity entity.
     *
     * @Route("/{id}/edit", name="admin_activity_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Activity $activity)
    {
        $deleteForm = $this->createDeleteForm($activity);

        if ($activity instanceof UnmanagedActivity) {
            $editForm = $this->createForm('AppBundle\Form\Type\UnmanagedActivityType', $activity);
        } else {
            $editForm = $this->createForm('AppBundle\Form\Type\ManagedActivityType', $activity);
        }
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('admin_activity_edit', array('id' => $activity->getId()));
        }

        return $this->render('admin/activity/edit.html.twig', array(
            'activity' => $activity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Activity entity.
     *
     * @Route("/{id}", name="admin_activity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Activity $activity)
    {
        $form = $this->createDeleteForm($activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($activity);
            $em->flush();
        }

        return $this->redirectToRoute('admin_activity_index');
    }

    /**
     * Creates a form to delete a Activity entity.
     *
     * @param Activity $activity The Activity entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Activity $activity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_activity_delete', array('id' => $activity->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
