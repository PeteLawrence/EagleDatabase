<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\MemberRegistration;
use AppBundle\Entity\MemberQualification;
use AppBundle\Services\MembershipService;

/**
 * @Route("/account/renew")
 */
class RenewController extends Controller
{
    /**
     * @Route("/", name="account_membership_renew")
     */
    public function membershipRenewAction(Request $request, MembershipService $membershipService)
    {
        $session = $request->getSession();

        // Check that the member is over 18
        $now = new \DateTime();
        $age = $this->getUser()->getDob()->diff($now);
        if ($age->y < 18) {
            return $this->redirectToRoute('account_membership_renew_error', [ 'errorCode' => 'UNDER18' ]);
        }

        // Get the next available MembershipTypePeriod, and save to the session
        $mtp = $membershipService->getNextMembershipTypePeriod($this->getUser());
        if (!$mtp) {
            return $this->redirectToRoute('account_membership_renew_error', [ 'errorCode' => 'NOMTP' ]);
        }
        $request->getSession()->set('renew_mtp', $mtp->getId());


        //Get available membership options
        $form = $membershipService->buildConfirmForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('account_membership_renew2');
        }

        return $this->render(
            'account/renew.html.twig',
            [
                'form' => $form->createView(),
                'nextMembershipTypePeriod' => $mtp
            ]
        );
    }


    /**
     * @Route("/insurance", name="account_membership_renew2")
     */
    public function membershipRenew2Action(Request $request,  MembershipService $membershipService)
    {
        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();

        ///
        $mtp = $em->getRepository('AppBundle:MembershipTypePeriod')->findOneById($session->get('renew_mtp'));

        //Get available membership options
        $form = $membershipService->buildMembershipExtrasForm($mtp, false);


        if ($request->getMethod() == 'POST') {
            /*$extras = [];
            $data = $request->request->get('form');
            foreach ($data as $field => $enabled) {
                if ($field == '_token') {
                    continue;
                }

                $extras[] = substr($field, 6);
            }*/
            $extras = [];
            // Add on insurance extra if they are not an active BC member
            if ($this->getUser()->getBcMembershipNumber() == '') {
                //Look up the relevant Insurance MembershipExtra
                $insuranceExtra = $em->getRepository('AppBundle:MembershipExtra')->findOneByName('BC Insurance');

                //Lookup the relevant Membership
                $insuranceMembershipTypePeriodExtra = $em->getRepository('AppBundle:MembershipTypePeriodExtra')->findOneBy([
                    'membershipTypePeriod' => $mtp,
                    'membershipExtra' => $insuranceExtra
                ]);

                if (!$insuranceMembershipTypePeriodExtra) {
                    return $this->redirectToRoute('account_membership_renew_error', [ 'errorCode' => 'NOINSURANCEEXTRA' ]);
                }

                $extras[] = $insuranceMembershipTypePeriodExtra->getId();
            }

            $session->set('renew_extras', $extras);

            return $this->redirectToRoute('account_membership_renew3');
        }


        // replace this example code with whatever you need
        return $this->render(
            'account/renew2.html.twig',
            [
                'form' => $form->createView(),
                'mtp' => $mtp
            ]
        );
    }


    /**
     * @Route("/summary", name="account_membership_renew3")
     */
    public function membershipRenew3Action(Request $request,  MembershipService $membershipService)
    {
        $session = $request->getSession();

        //Create the MemberRegistration record
        $membershipTypePeriodId = $session->get('renew_mtp');
        $membershipTypePeriodExtraIds = $session->get('renew_extras');
        $memberRegistration = $membershipService->buildMembershipRegistration($membershipTypePeriodId, $membershipTypePeriodExtraIds, $this->getUser());


        // replace this example code with whatever you need
        return $this->render(
            'account/renew3.html.twig',
            [
                'form' => $this->buildStripePaymentForm()->createView(),
                'payLaterForm' => $this->buildPayLaterForm()->createView(),
                'memberRegistration' => $memberRegistration,
                'stripe_publishable_key' => $this->getParameter('stripe.publishable_key'),
                'total' => $memberRegistration->getTotal()
            ]
        );
    }


    /**
     * @Route("/paynow", name="account_membership_renew_paynow")
     */
    public function membershipRenewPayNowAction(Request $request,  MembershipService $membershipService)
    {
        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();

        //Create the MemberRegistration record
        $membershipTypePeriodId = $session->get('renew_mtp');
        $membershipTypePeriodExtraIds = $session->get('renew_extras');
        $memberRegistration = $membershipService->buildMembershipRegistration($membershipTypePeriodId, $membershipTypePeriodExtraIds, $this->getUser());

        $form = $this->buildStripePaymentForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Configure Stripe
             \Stripe\Stripe::setApiKey($this->getParameter('stripe.secret_key'));

            // Get the credit card details submitted by the form
            $data = $form->getData();
            $token = $data['stripeToken'];

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

            $membershipService->clearSessionEntries($session);

            return $this->redirectToRoute('account_membership_renew_complete');
        }

    }


    /**
     * @Route("/paylater", name="account_membership_renew_paylater")
     */
    public function membershipRenewPayLaterAction(Request $request,  MembershipService $membershipService)
    {
        $em = $this->get('doctrine')->getManager();
        $session = $request->getSession();

        //Create the MemberRegistration record
        $membershipTypePeriodId = $session->get('renew_mtp');
        $membershipTypePeriodExtraIds = $session->get('renew_extras');

        $memberRegistration = $membershipService->buildMembershipRegistration($membershipTypePeriodId, $membershipTypePeriodExtraIds, $this->getUser());

        $em->persist($memberRegistration);

        //Create a MemberRegistrationCharge and mark as needing paying
        $now = new \DateTime();
        $dueDate = clone $now;
        $dueDate->add(new \DateInterval('P7D'));

        $memberRegistrationCharge = new \AppBundle\Entity\MemberRegistrationCharge();
        $memberRegistrationCharge->setDescription('Membership');
        $memberRegistrationCharge->setAmount($memberRegistration->getTotal());
        $memberRegistrationCharge->setPaid(false);
        $memberRegistrationCharge->setDuedatetime($dueDate);
        $memberRegistrationCharge->setCreateddatetime($now);
        $memberRegistrationCharge->setMemberRegistration($memberRegistration);
        $memberRegistrationCharge->setPerson($memberRegistration->getPerson());
        $em->persist($memberRegistrationCharge);

        $memberRegistration->setMemberRegistrationCharge($memberRegistrationCharge);

        $em->flush();

        $membershipService->clearSessionEntries($session);
        $this->sendRenewSuccessEmails($this->getUser(), $memberRegistration);

        return $this->redirectToRoute('account_membership_renew_complete');
    }


    /**
     * @Route("/complete", name="account_membership_renew_complete")
     */
    public function membershipRenewCompleteAction(Request $request, MembershipService $membershipService)
    {
        $latestMemberRegistration = $this->getUser()->getMostRecentRegistration();


        return $this->render(
            'account/renewComplete.html.twig', [
                'registration' => $latestMemberRegistration
            ]
        );
    }


    /**
     * @Route("/error", name="account_membership_renew_error")
     */
    public function membershipErrorAction(Request $request)
    {
        return $this->render(
            'account/renewError.html.twig', [
                'errorCode' => $request->query->get('errorCode')
            ]
        );
    }


    private function buildStripePaymentForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('account_membership_renew_paynow'))
            ->add('stripeToken', HiddenType::class, [ 'attr' => ['id' => 'stripetoken'] ])
            ->getForm();
    }


    private function buildPayLaterForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('account_membership_renew_paylater'))
            ->getForm();
    }


    private function sendRenewSuccessEmails($member, $memberRegistration)
    {
        //Send a confirmation email to the member
        $message = new \Swift_Message('Renewal Email');
        $message
            ->setSubject(sprintf('You have successfully re-enrolled'))
            ->setFrom($this->getParameter('site.email'))
            ->setTo($member->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/renewSuccessEmailMember.html.twig',
                    [
                        'person' => $member,
                        'memberRegistration' => $memberRegistration
                    ]
                ),
                'text/html'
            );

        $mailer = $this->get('mailer');
        $mailer->send($message);
    }
}
