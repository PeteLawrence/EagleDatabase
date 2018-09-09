<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $person = new \AppBundle\Entity\Person;

        $form = $this->buildRegisterForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $encoder = $this->container->get('security.password_encoder');
            $encodedPassword = $encoder->encodePassword($person, $data->password);
            $person->setPassword($encodedPassword);

            //Persist the person
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            //Automatically log the user in
            $token = new UsernamePasswordToken($person, null, 'main', $person->getRoles());
            $this->get('security.token_storage')->setToken($token);

            //Redirect to account
            return $this->redirectToRoute('account_overview');
        }

        return $this->render(
            'register/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }


    private function buildRegisterForm($user)
    {
        return $this->createFormBuilder($user)
            ->setAction($this->generateUrl('register'))
            ->setMethod('POST')
            ->add('forename', TextType::class, [ 'required' => true, 'attr' => ['placeholder' => 'Forename(s)'] ])
            ->add('surname', TextType::class, [ 'attr' => ['placeholder' => 'Surname'] ])
            ->add('email', EmailType::class, [ 'attr' => ['placeholder' => 'Email Address'] ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'Password', 'attr' => ['placeholder' => 'Password']),
                'second_options' => array('label' => 'Repeat Password', 'attr' => ['placeholder' => 'Repeat Password']),
            ))
            ->getForm()
        ;
    }
}
