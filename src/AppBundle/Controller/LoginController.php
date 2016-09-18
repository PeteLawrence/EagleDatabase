<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
            $person = $em->getRepository('AppBundle:Person')->findOneByEmail($data['email']);

            if ($person) {
                //Generate a password reset token
                $generator = new ComputerPasswordGenerator();
                $generator->setUppercase()->setLowercase()->setNumbers()->setSymbols(false)->setLength(32);
                $resetToken = $generator->generatePassword();
                $now = new \DateTime();
                $resetTokenExpiry = $now->add(new \DateInterval('PT20M'));

                //Blank the old password, and populate the reset token
                $person->setPassword(null);
                $person->setPasswordResetToken($resetToken);
                $person->setPasswordResetTokenExpiry($resetTokenExpiry);

                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('EagleDB Password Reset')
                    ->setFrom('pete@tabs2.co.uk')
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
            //Lookup the user
            $encoder = $this->container->get('security.password_encoder');
            $encodedPassword = $encoder->encodePassword($person, $data['password']);

            $person->setPassword($encodedPassword);
            $person->setPasswordResetToken(null);
            $person->setPasswordResetTokenExpiry(null);

            $em->flush();

            $this->addFlash('notice', 'Your password has been reset');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'login/newpassword.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    private function createPasswordResetForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reset_password'))
            ->setMethod('POST')
            ->add('email', TextType::class, [ 'attr' => ['placeholder' => 'Email Address' ]])
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
}
