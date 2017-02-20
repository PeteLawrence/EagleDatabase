<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Form\StripePaymentForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\MemberRegistration;

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
     * @Route("/membership/renew", name="account_membership_renew")
     */
    public function membershipRenewAction(Request $request)
    {
        $membershipService = $this->get('membership_service');

        //Get available membership options
        $form = $membershipService->buildMembershipTypeForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $request->getSession()->set('renew_mtp', $data['membershipTypePeriod']->getId());

            return $this->redirectToRoute('account_membership_renew2');
        }

        return $this->render(
            'account/renew.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/membership/renew2", name="account_membership_renew2")
     */
    public function membershipRenew2Action(Request $request)
    {
        $membershipService = $this->get('membership_service');

        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();

        $mtp = $em->getRepository('AppBundle:MembershipTypePeriod')->findOneById($session->get('renew_mtp'));

        //Get available membership options
        $form = $membershipService->buildMembershipExtrasForm($mtp);


        if ($request->getMethod() == 'POST') {
            $extras = [];
            $data = $request->request->get('form');
            foreach ($data as $field => $enabled) {
                if ($field == '_token') {
                    continue;
                }

                $extras[] = substr($field, 6);
            }
            $session->set('renew_extras', $extras);

            return $this->redirectToRoute('account_membership_renew3');
        }


        // replace this example code with whatever you need
        return $this->render(
            'account/renew2.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/membership/renew3", name="account_membership_renew3")
     */
    public function membershipRenew3Action(Request $request)
    {
        $membershipService = $this->get('membership_service');
        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();

        $total = 0;

        //Create the MemberRegistration record
        $membershipTypePeriodId = $session->get('renew_mtp');
        $membershipTypePeriodExtraIds = $session->get('renew_extras');
        $memberRegistration = $membershipService->buildMembershipRegistration($membershipTypePeriodId, $membershipTypePeriodExtraIds);

        //Get available membership options
        $form = $this->buildStripePaymentForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Configure Stripe
             \Stripe\Stripe::setApiKey($this->getParameter('stripe.secret_key'));

            // Get the credit card details submitted by the form
            $token = $_POST['stripeToken'];

            // Create a charge: this will charge the user's card
            try {
                $charge = \Stripe\Charge::create(array(
                    "amount" => ($memberRegistration->getTotal() * 100), // Amount in pence
                    "currency" => "gbp",
                    "source" => $token,
                    "description" => "Membership"
                ));
            } catch (\Stripe\Error\Card $e) {
                // The card has been declined
                throw new \Exception('There was an error taking your payment.');
            }

            $memberRegistration->setRegistrationDateTime(new \DateTime());
            $memberRegistration->setPerson($this->getUser());
            $em->persist($memberRegistration);

            //Create a MemberRegistrationCharge and mark as paid
            $memberRegistrationCharge = new \AppBundle\Entity\MemberRegistrationCharge();
            $memberRegistrationCharge->setDescription('Membership');
            $memberRegistrationCharge->setAmount($charge->amount/100);
            $memberRegistrationCharge->setReference($charge->id);
            $memberRegistrationCharge->setPaid(true);
            $memberRegistrationCharge->setPaiddatetime(new \DateTime());
            $memberRegistrationCharge->setDuedatetime(new \DateTime());
            $memberRegistrationCharge->setCreateddatetime(new \DateTime());
            $memberRegistrationCharge->setMemberRegistration($memberRegistration);
            $memberRegistrationCharge->setPerson($memberRegistration->getPerson());
            $em->persist($memberRegistrationCharge);

            $em->flush();

            $this->addFlash('notice', 'Your renewal has been successful');

            return $this->redirectToRoute('account_overview');
        }

        // replace this example code with whatever you need
        return $this->render(
            'account/renew3.html.twig',
            [
                'form' => $form->createView(),
                'memberRegistration' => $memberRegistration,
                'stripe_publishable_key' => $this->getParameter('stripe.publishable_key'),
                'total' => $memberRegistration->getTotal()
            ]
        );
    }

    private function buildStripePaymentForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->getForm();
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
            ->add('forename', TextType::class, [ 'attr' => ['placeholder' => 'Forename(s)'] ])
            ->add('surname', TextType::class, [ 'attr' => ['placeholder' => 'Surname'] ])
            ->add('email', EmailType::class, [ 'attr' => ['placeholder' => 'Email Address'] ])
            ->add('gender', ChoiceType::class, [ 'choices' => [ 'Female' => 'F', 'Male' => 'M'] ])
            ->add('dob', BirthdayType::class, [ 'widget' => 'choice', 'label' => 'D.o.B', 'format' => 'd MMMM y', 'years' => range(1910, date('Y')) ])
            ->add('addr1', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 1'] ])
            ->add('addr2', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 2'], 'required' => false ])
            ->add('addr3', TextType::class, [ 'attr' => ['placeholder' => 'Address Line 3'], 'required' => false ])
            ->add('town', TextType::class, [ 'attr' => ['placeholder' => 'Town'] ])
            ->add('county', TextType::class, [ 'attr' => ['placeholder' => 'County'] ])
            ->add('postcode', TextType::class, [ 'attr' => ['placeholder' => 'Postcode'] ])
            ->add('telephone', TextType::class, [ 'attr' => ['placeholder' => 'Phone Number'] ])
            ->add('mobile', TextType::class, [ 'attr' => ['placeholder' => 'Mobile Number'] ])
            ->add('nextOfKinName', TextType::class, [ 'attr' => ['placeholder' => 'Name'], 'label' => 'Name' ])
            ->add('nextOfKinRelation', TextType::class, [ 'attr' => ['placeholder' => 'Relation'], 'label' => 'Relation' ])
            ->add('nextOfKinContactDetails', TextareaType::class, [ 'attr' => ['placeholder' => 'Contact Details', 'rows' => 3], 'label' => 'Contact Details' ])
            ->getForm()
        ;
    }
}
