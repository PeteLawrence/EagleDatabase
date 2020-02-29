<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Person;
use AppBundle\Entity\MemberQualification;
use AppBundle\Entity\MemberRegistration;
use AppBundle\Form\Type\PersonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Services\PersonReportService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Services\PersonService;

/**
 * Person controller.
 *
 * @Route("/person")
 */
class PersonController extends Controller
{
    /**
     * Lists all Person entities.
     *
     * @Route("/", name="admin_person_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository('AppBundle:Person')->findAll();

        return $this->render('admin/person/index.html.twig', array(
            'people' => $people,
        ));
    }

    /**
     * Creates a new Person entity.
     *
     * @Route("/new", name="admin_person_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('admin_person_show', array('id' => $person->getId()));
        }

        return $this->render('admin/person/new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Person entity.
     *
     * @Route("/{id}", name="admin_person_show", methods={"GET"})
     */
    public function showAction(Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);

        return $this->render('admin/person/show.html.twig', array(
            'person' => $person,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/edit", name="admin_person_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Person $person)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($person);

        $editForm = $this->createForm('AppBundle\Form\Type\PersonType', $person);
        $editForm->handleRequest($request);

        $nextOfKinForm = $this->buildNextOfKinForm($person);
        $nextOfKinForm->handleRequest($request);

        //$groupsForm = $this->buildGroupsForm($person);
        //$groupsForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', [ 'id' => $person->getid() ]);
        }

        if ($nextOfKinForm->isSubmitted() && $nextOfKinForm->isValid()) {
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', [ 'id' => $person->getid() ]);
        }

        /*if ($groupsForm->isSubmitted() && $groupsForm->isValid()) {
            dump($person);
            die();
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', [ 'id' => $person->getid() ]);
        }*/

        $participations = $em->getRepository('AppBundle:Participant')->findByPersonOrdered($person);

        return $this->render('admin/person/edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'nextofkin_form' => $nextOfKinForm->createView(),
            //'groups_form' => $groupsForm->createView(),
            'participations' => $participations
        ));
    }


    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}/qualification/{id2}/verify", name="admin_person_qualification_verify")
     * @ParamConverter("memberQualification", class="AppBundle:MemberQualification", options={"id" = "id2"})
     */
    public function verifyQualificationAction(Request $request, Person $person, MemberQualification $memberQualification)
    {
        $form = $this->buildVerifyQualificationForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->get('security.token_storage')->getToken()->getUser();

            $memberQualification->setVerifiedBy($currentUser);
            $memberQualification->setVerifiedDateTime(new \DateTime());

            //Persist changes
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', [ 'id' => $person->getId()]);
        }

        return $this->render('admin/person/verifyQualification.html.twig', array(
            'person' => $person,
            'memberQualification' => $memberQualification,
            'form' => $form->createView()
        ));
    }

    private function buildVerifyQualificationForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->getForm()
        ;
    }


    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}/memberregistration/{id2}/delete", name="admin_person_registration_delete")
     * @ParamConverter("memberRegistration", class="AppBundle:MemberRegistration", options={"id" = "id2"})
     */
    public function deleteMembershipRegistrationAction(Request $request, Person $person, MemberRegistration $memberRegistration)
    {
        $form = $this->createDeleteMembershipRegistrationForm($memberRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Persist changes
            $em = $this->getDoctrine()->getManager();
            $em->remove($memberRegistration);
            $em->flush();

            return $this->redirectToRoute('admin_person_edit', [ 'id' => $person->getId()]);
        }

        return $this->render('admin/person/deleteMemberRegistration.html.twig', array(
            'person' => $person,
            'memberRegistration' => $memberRegistration,
            'form' => $form->createView()
        ));
    }



    /**
     * Deletes a Person entity.
     *
     * @Route("/{id}", name="admin_person_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Person $person)
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($person);
            $em->flush();
        }

        return $this->redirectToRoute('admin_person_index');
    }


    /**
     * Forgets a Person entity.
     *
     * @Route("/{id}/forget", name="admin_person_forget_confirm", methods={"GET"})
     */
    public function confirmForgetAction(Request $request, Person $person, PersonService $personService)
    {
        $form = $this->createForgetForm($person);


        return $this->render('admin/person/confirmForget.html.twig', array(
            'person' => $person,
            'form' => $form->createView()
        ));
    }


    /**
     * Forgets a Person entity.
     *
     * @Route("/{id}/forget", name="admin_person_forget_confirmed", methods={"DELETE"})
     */
    public function confirmedForgetAction(Request $request, Person $person, PersonService $personService)
    {
        $form = $this->createForgetForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personService->forgetPerson($person);

            //Display confirmation flash message
            $this->addFlash('notice', 'This person has now been forgotten.  If they ever wish to rejoin they can quote the following ID to resume their account: ' . $person->getId());
        }

        return $this->redirectToRoute('admin_person_index');
    }


    /**
     * Forgets a Person entity.
     *
     * @Route("/{id}/forget", name="admin_person_forget", methods={"DELETE"})
     */
    public function forgetAction(Request $request, Person $person, PersonService $personService)
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personService->forgetPerson($person);
        }

        return $this->redirectToRoute('admin_person_index');
    }


    /**
     * Displays a form to edit an existing Person entity.
     *
     * @Route("/{id}/stats", name="admin_person_stats", methods={"GET", "POST"})
     */
    public function statsAction(Request $request, Person $person, PersonReportService $personReportService)
    {
        $timeline = $personReportService->buildParticipationTimeline($person);



        return $this->render('admin/person/stats.html.twig', array(
            'person' => $person,
            'timeline' => $timeline
        ));
    }

    /**
     * Creates a form to delete a Person entity.
     *
     * @param Person $person The Person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_person_delete', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Creates a form to delete a MemberRegistration entity.
     *
     * @param MemberRegistration $memberRegistration The MemberRegistration entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteMembershipRegistrationForm(MemberRegistration $memberRegistration)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_person_registration_delete', [
                'id' => $memberRegistration->getPerson()->getId(),
                'id2' => $memberRegistration->getId()
            ]))
            ->setMethod('DELETE')
            ->getForm();
    }



    /**
     * Creates a form to forget a Person entity.
     *
     * @param Person $person The Person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createForgetForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_person_forget', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    private function buildNextOfKinForm($user)
    {
        return $this->createFormBuilder($user)
            ->setAction($this->generateUrl('admin_person_edit', [ 'id' => $user->getId() ]))
            ->setMethod('POST')
            ->add('nextOfKinName', TextType::class, [ 'attr' => ['placeholder' => 'Name'], 'label' => 'Name' ])
            ->add('nextOfKinRelation', TextType::class, [ 'attr' => ['placeholder' => 'Relation'], 'label' => 'Relation' ])
            ->add('nextOfKinContactDetails', TextareaType::class, [ 'attr' => ['placeholder' => 'Contact Details', 'rows' => 3], 'label' => 'Contact Details' ])
            ->getForm()
        ;
    }

    private function buildGroupsForm($user)
    {
        return $this->createFormBuilder($user)
            ->setAction($this->generateUrl('admin_person_edit', [ 'id' => $user->getId() ]))
            ->setMethod('POST')
            ->add('group', EntityType::class, [
                'class' => 'AppBundle:Group',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                }
            ])
            ->getForm()
        ;
    }
}
