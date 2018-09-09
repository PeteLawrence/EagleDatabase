<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }


    /**
     * @Route("/resetpassword", name="reset_password")
     */
    public function resetPasswordAction(Request $request)
    {
        $form = $this->createPasswordResetForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //Lookup the user
            $em = $this->get('doctrine')->getManager();
            $person = $em->getRepository('AppBundle:Person')->findOneById($data['membershipNumber']);

            if ($person && strtolower($person->getEmail()) == strtolower($data['email'])) {
                //Generate a password reset token
                $generator = new ComputerPasswordGenerator();
                $generator->setUppercase()->setLowercase()->setNumbers()->setSymbols(false)->setLength(32);
                $resetToken = $generator->generatePassword();
                $now = new \DateTime();
                $resetTokenExpiry = $now->add(new \DateInterval('PT6H'));

                //Blank the old password, and populate the reset token
                $person->setPassword('XXX'); //Use 'XXX' as field cannot be emtpy.
                $person->setPasswordResetToken($resetToken);
                $person->setPasswordResetTokenExpiry($resetTokenExpiry);

                $em->flush();

                $message = new \Swift_Message('Email');
                $message
                    ->setSubject(sprintf('%s Password Reset', $this->getParameter('site.name')))
                    ->setFrom($this->getParameter('site.email'))
                    ->setTo($person->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/resetPassword.html.twig',
                            array('person' => $person, 'resetToken' => $resetToken)
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);

                $this->addFlash('notice', 'You have been emailed instructions');
            } else {
                $this->addFlash('warning', 'No account could be found with the details that you entered.');
            }
        }

        return $this->render(
            'login/resetpassword.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * Resets a users passord
     *
     * @Route("/resetpassword/{token}", name="reset_password_token")
     */
    public function resetPasswordTokenAction(Request $request, $token)
    {
        $em = $this->get('doctrine')->getManager();

        // Check that this supplied token matches a user
        $person = $em->getRepository('AppBundle:Person')->findOneByPasswordResetToken($token);
        if (!$person) {
            throw $this->createNotFoundException('Token was invalid');
        }

        //Check that the expiration time hasn't passed
        $now = new \DateTime();
        if ($now > $person->getPasswordResetTokenExpiry()) {
            throw $this->createNotFoundException('Token was out of date');
        }

        $form = $this->createNewPasswordForm($token);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            //Encrypt the supplied password
            $encodedPassword = $this->encodePassword($person, $data['password']);

            //Update the users password, and reset the reset token fields
            $person->setPassword($encodedPassword);
            $person->setPasswordResetToken(null);
            $person->setPasswordResetTokenExpiry(null);

            $em->flush();

            $this->addFlash('notice', 'Your password has been reset');

            //Automatically log the user in
            $token = new UsernamePasswordToken($person, null, 'main', $person->getRoles());
            $this->get('security.token_storage')->setToken($token);

            return $this->redirectToRoute('account_overview');
        }

        return $this->render(
            'login/newpassword.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * Resets a users passord
     *
     * @Route("/lookup", name="lookup_membership_number")
     */
    public function membershipNumberLookupAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        $form = $this->createMembershipNumberLookupForm();
        $form->handleRequest($request);

        $results = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $results = $em->getRepository('AppBundle:Person')->findMembersByEmailAndDob($data['email'], $data['dob']);
            /*if (!$results) {
                throw $this->createNotFoundException('No such user');
            }*/

            if (sizeof($results) == 0) {
                $this->addFlash('warning', 'Sorry, we couldn\'t find a member with the details supplied.');
            }
        }


        return $this->render(
            'login/lookupMembershipNumber.html.twig',
            [
                'form' => $form->createView(),
                'results' => $results
            ]
        );
    }


    private function createMembershipNumberLookupForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->add('email', EmailType::class, [ 'attr' => ['placeholder' => 'Email Address'], 'label' => 'Email Address' ])
            ->add('dob', BirthdayType::class, ['label' => 'Date Of Birth', 'format' => 'd MMMM y', 'years' => range(date('Y') - 100, date('Y'))])
            ->getForm();
    }


    private function createPasswordResetForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reset_password'))
            ->setMethod('POST')
            ->add('membershipNumber', TextType::class, [ 'attr' => ['placeholder' => 'Membership Number' ], 'label' => 'Membership Number'])
            ->add('email', EmailType::class, [ 'attr' => [' placeholder' => 'Email Address'], 'label' => 'Email Address'])
            ->getForm()
        ;
    }

    private function createNewPasswordForm($token)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reset_password_token', [ 'token' => $token]))
            ->setMethod('POST')
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->getForm()
        ;
    }


    private function encodePassword($person, $password)
    {
        $encoder = $this->container->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($person, $password);

        return $encodedPassword;
    }
}
