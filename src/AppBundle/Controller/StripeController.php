<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/stripe")
 */
class StripeController extends Controller
{
    /**
     * @Route("/callback", name="stripe_callback")
     */
    public function indexAction(Request $request)
    {
        //Get the currently logged in user
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();

        //Lookup membership type
        $membershipTypePeriod = $em->getRepository('AppBundle:MembershipTypePeriod')->findOneById($request->request->get('form')['membershipType']);


        //Check that the user hasn't previously paid
        foreach ($user->getMemberRegistration() as $registration) {
            if ($registration->getMembershipTypePeriod()->getMembershipPeriod() == $membershipTypePeriod->getMembershipPeriod()) {
                throw new \Exception('You are already registered.  You will NOT be charged.');
            }
        }


        \Stripe\Stripe::setApiKey("sk_test_X2GvJmrdbEAxu0HJjEE2jfqA");

        // Get the credit card details submitted by the form
        $token = $_POST['stripeToken'];


        // Create a charge: this will charge the user's card
        try {
            $charge = \Stripe\Charge::create(array(
            "amount" => 7500, // Amount in cents
            "currency" => "gbp",
            "source" => $token,
            "description" => "Example charge"
            ));
        } catch (\Stripe\Error\Card $e) {
            // The card has been declined
            throw new \Exception('There was an error taking your payment.');
        }


        $memberRegistration = new \AppBundle\Entity\MemberRegistration();
        $memberRegistration->setPerson($user);
        $memberRegistration->setMembershipTypePeriod($membershipTypePeriod);
        $memberRegistration->setRegistrationDateTime(new \DateTime());

        $em->persist($memberRegistration);
        $em->flush();


        $this->addFlash('notice', 'Your renewal has been successful');

        return $this->redirectToRoute('account_overview');
    }
}
