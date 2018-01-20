<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\StripePaymentForm;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\MemberRegistration;
use AppBundle\Entity\MemberQualification;
use AppBundle\Services\MembershipService;

/**
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * @Route("/", name="account_overview")
     */
    public function indexAction(Request $request, MembershipService $membershipService)
    {
        $em = $this->get('doctrine')->getManager();
        $activityRepository = $em->getRepository('AppBundle:ManagedActivity');

        $upcomingActivities = $activityRepository->findUpcomingActivities($this->getUser(), 3);

        $nextMtp = $membershipService->getNextMembershipTypePeriod($this->getUser());

        // replace this example code with whatever you need
        return $this->render('account/overview.html.twig',
            [
                'upcomingActivities' => $upcomingActivities,
                'canRenew' => $membershipService->canPersonRenew($this->getUser())
            ]
        );
    }


    /**
     * @Route("/activities", name="account_activities")
     */
    public function activitiesAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $participations = $em->getRepository('AppBundle:Participant')->findByPersonOrdered($this->getUser());

        // replace this example code with whatever you need
        return $this->render('account/activities.html.twig', [
            'participations' => $participations
        ]);
    }


    /**
     * @Route("/membership", name="account_membership")
     */
    public function membershipAction(Request $request, MembershipService $membershipService)
    {
        // replace this example code with whatever you need
        return $this->render('account/membership.html.twig', [
            'canRenew' => $membershipService->canPersonRenew($this->getUser())
        ]);
    }


    /**
     * @Route("/charges", name="account_charges")
     */
    public function chargesAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('account/charges.html.twig');
    }


    /**
     * @Route("/qualifications", name="account_qualifications")
     */
    public function qualificationsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('account/qualifications.html.twig');
    }



    /**
     * @Route("/qualifications/new", name="account_qualifications_new")
     */
    public function qualificationsNewAction(Request $request)
    {
        $memberQualification = new \AppBundle\Entity\MemberQualification();
        $form = $this->createForm('AppBundle\Form\Type\MemberQualificationTypePerson', $memberQualification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Set the person on the member qualification
            $memberQualification->setPerson($this->get('security.token_storage')->getToken()->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($memberQualification);
            $em->flush();

            $this->addFlash('notice', sprintf('Your %s qualification has been added', $memberQualification->getQualification()->getName()));

            return $this->redirectToRoute('account_qualifications');
        }

        // replace this example code with whatever you need
        return $this->render('account/qualificationsNew.html.twig',[
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/qualifications/{id}", name="account_qualifications_edit")
     */
    public function qualificationsEditAction(Request $request, MemberQualification $memberQualification)
    {
        //Only allow members to edit their own qualifications
        if ($memberQualification->getPerson() != $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm('AppBundle\Form\Type\MemberQualificationTypePerson', $memberQualification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Set the person on the member qualification
            $memberQualification->setPerson($this->get('security.token_storage')->getToken()->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($memberQualification);
            $em->flush();

            $this->addFlash('notice', sprintf('Your %s qualification has been saved', $memberQualification->getQualification()->getName()));

            return $this->redirectToRoute('account_qualifications');
        }

        // replace this example code with whatever you need
        return $this->render('account/qualificationsEdit.html.twig',[
            'form' => $form->createView(),
            'memberQualification' => $memberQualification
        ]);
    }



    /**
     * @Route("/qualifications/{id}/delete", name="account_qualifications_delete")
     */
    public function qualificationsDeleteAction(Request $request, MemberQualification $memberQualification)
    {
        //Only allow members to delete their own qualifications
        if ($memberQualification->getPerson() != $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteForm($memberQualification);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($memberQualification);
            $em->flush();

            $this->addFlash('notice', sprintf('Your %s qualification has been deleted', $memberQualification->getQualification()->getName()));

            return $this->redirectToRoute('account_qualifications');
        }

        // replace this example code with whatever you need
        return $this->render('account/qualificationsDelete.html.twig',[
            'deleteForm' => $deleteForm->createView(),
            'memberQualification' => $memberQualification
        ]);
    }


    private function createDeleteForm(MemberQualification $memberQualification)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('account_qualifications_delete', array('id' => $memberQualification->getId() )))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * @Route("/membership/{id}/view", name="account_membership_detail")
     */
    public function membershipDetailAction(Request $request, MemberRegistration $memberRegistration)
    {
        // replace this example code with whatever you need
        return $this->render(
            'account/membershipdetail.html.twig',
            [
                'memberRegistration' => $memberRegistration
            ]
        );
    }


    /**
     * @Route("/edit", name="account_edit")
     */
    public function editAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->buildNameForm($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Save the changes
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Your changes were saved!');

            return $this->redirectToRoute('account_edit');
        }

        return $this->render(
            'account/edit.html.twig',
            [
                'user' => $user,
                'nameForm' => $form->createView()
            ]
        );
    }



    private function buildNameForm($user)
    {
        return $this->createFormBuilder($user)
            ->setAction($this->generateUrl('account_edit'))
            ->setMethod('POST')
            ->add('forename', TextType::class, [ 'attr' => ['placeholder' => 'Forename(s)']])
            ->add('surname', TextType::class, [ 'attr' => ['placeholder' => 'Surname'] ])
            ->add('email', EmailType::class, [ 'attr' => ['placeholder' => 'Email Address'] ])
            ->add('gender', ChoiceType::class, [ 'choices' => [ 'Female' => 'F', 'Male' => 'M'] ])
            ->add('bcMembershipNumber', TextType::class, [ 'attr' => ['placeholder' => 'British Canoeing Membership Number'], 'label' => 'British Canoeing Membership Number', 'required' => false ])
            ->add('dob', BirthdayType::class, [ 'widget' => 'choice', 'label' => 'D.o.B', 'format' => 'd MMMM y', 'years' => range(1910, date('Y')) ])
            ->add('addr1', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 1'] ])
            ->add('addr2', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 2'], 'required' => false ])
            ->add('addr3', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 3'], 'required' => false ])
            ->add('town', TextType::class, [ 'attr' => ['placeholder' => 'Town'] ])
            ->add('county', TextType::class, [ 'attr' => ['placeholder' => 'County'] ])
            ->add('postcode', TextType::class, [ 'attr' => ['placeholder' => 'Postcode'] ])
            ->add('telephone', TextType::class, [ 'attr' => ['placeholder' => 'Phone Number'], 'required' => false ])
            ->add('mobile', TextType::class, [ 'attr' => ['placeholder' => 'Mobile Number'], 'required' => false ])
            ->add('nextOfKinName', TextType::class, [ 'attr' => ['placeholder' => 'Name'], 'label' => 'Name' ])
            ->add('nextOfKinRelation', TextType::class, [ 'attr' => ['placeholder' => 'Relation'], 'label' => 'Relation' ])
            ->add('nextOfKinContactDetails', TextareaType::class, [ 'attr' => ['placeholder' => 'Contact Details', 'rows' => 3], 'label' => 'Contact Details' ])
            ->getForm()
        ;
    }



    private function buildQualificationForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->add('qualification', EntityType::class, [ 'attr' => ['placeholder' => 'Forename(s)'], 'class' => 'AppBundle:Qualification' ])
            ->add('validFrom', DateType::class, [ 'html5' => true, 'widget' => 'choice', 'format' => 'd MMMM y', 'required' => true, 'years' => range(1970, 2030) ])
            ->add('validTo', DateType::class, [ 'html5' => true, 'widget' => 'choice', 'format' => 'd MMMM y', 'label' => 'Valid To (if applicable)', 'required' => false, 'years' => range(1970, 2030) ])
            ->add('reference', TextType::class, [ 'attr' => ['placeholder' => 'Certificate #, etc.']])
            ->add('notes', TextareaType::class, [ 'attr' => ['placeholder' => 'Notes', 'rows' => 3], 'label' => 'Notes', 'required' => false ])
            ->getForm()
        ;
    }
}
