<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Form\StripePaymentForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @Route("/account")
 */
class AccountController extends Controller
{
    /**
     * @Route("/", name="account_overview")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('account/overview.html.twig');
    }


    /**
     * @Route("/activities", name="account_activities")
     */
    public function activitiesAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('account/activities.html.twig');
    }


    /**
     * @Route("/membership", name="account_membership")
     */
    public function membershipAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('account/membership.html.twig');
    }


    /**
     * @Route("/membership/renew", name="account_membership_renew")
     */
    public function membershipRenewAction(Request $request)
    {
        //Get available membership options
        $renewForm = $this->buildStripePaymentForm();

        // replace this example code with whatever you need
        return $this->render(
            'account/renew.html.twig',
            [
                'renewForm' => $renewForm->createView()
            ]
        );
    }


    private function buildStripePaymentForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stripe_callback'))
            ->setMethod('POST')
            ->add('membershipType',
                EntityType::class,
                [
                    'class' => \AppBundle\Entity\MembershipTypePeriod::class,
                    'choices' => $this->getAvailableMembershipTypes(),
                    'choice_label' => function ($mtp) {
                        return sprintf(
                            '%s: %s - %s £%s',
                            $mtp->getMembershipType()->getType(),
                            $mtp->getMembershipPeriod()->getFromDate()->format('j M Y'),
                            $mtp->getMembershipPeriod()->getToDate()->format('j M Y'),
                            $mtp->getPrice()
                        );
                    }
                ])
            ->getForm();
    }

    private function getAvailableMemberShipTypes()
    {
        $em = $this->get('doctrine')->getEntityManager();
        $membershipTypePeriods = $em->getRepository('AppBundle:MembershipTypePeriod')->findAll();
        $now = new \DateTime();

        $options = [];
        foreach ($membershipTypePeriods as $mtp) {
            $mp = $mtp->getMembershipPeriod();
            if (($mp->getFromDate() < $now) && ($mp->getToDate() > $now)) {
                $options[] = $mtp;
            }
        }

        return $options;
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



    public function buildNameForm($user)
    {
        return $this->createFormBuilder($user)
            ->setAction($this->generateUrl('account_edit'))
            ->setMethod('POST')
            ->add('forename', TextType::class, [ 'attr' => ['placeholder' => 'Forename(s)'] ])
            ->add('surname', TextType::class, [ 'attr' => ['placeholder' => 'Surname'] ])
            ->add('email', EmailType::class, [ 'attr' => ['placeholder' => 'Email Address'] ])
            ->add('gender', ChoiceType::class, [ 'choices' => [ 'Female' => 'F', 'Male' => 'M'] ])
            ->add('dob', BirthdayType::class, [ 'widget' => 'choice', 'label' => 'D.o.B', 'format' => 'd MMM y', 'years' => range(1910, date('Y')) ])
            ->add('addr1', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 1'] ])
            ->add('addr2', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 2'] ])
            ->add('town', TextType::class, [ 'attr' => ['placeholder' => 'Town'] ])
            ->add('county', TextType::class, [ 'attr' => ['placeholder' => 'County'] ])
            ->add('postcode', TextType::class, [ 'attr' => ['placeholder' => 'Postcode'] ])
            ->add('telephone', TextType::class, [ 'attr' => ['placeholder' => 'Phone Number'] ])
            ->add('mobile', TextType::class, [ 'attr' => ['placeholder' => 'Mobile Number'] ])
            ->getForm()
        ;
    }
}
