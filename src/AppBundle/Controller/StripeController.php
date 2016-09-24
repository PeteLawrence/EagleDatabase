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
        $em = $this->getDoctrine()->getEntityManager();

        //Lookup membership type
        $membershipTypePeriod = $em->getRepository('AppBundle:MembershipTypePeriod')->findOneById($request->request->get('form')['membershipType']);


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
        }


        $user = $this->get('security.token_storage')->getToken()->getUser();

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
