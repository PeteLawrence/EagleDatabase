<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Person;

class SignInController extends Controller
{
    /**
     * Displays a sign-in page
     *
     * @Route("/signin/{id}", name="signin_show")
     * @Method({"GET", "POST"})
     */
    public function signinAction(Request $request, Activity $activity)
    {
        //Check to see if the signin key is valid
        $signInKey = $request->get('key');
        if ($signInKey === null || $signInKey != $activity->getSigninKey()) {
            throw $this->createAccessDeniedException('Sign In Key was invalid');
        }


        $form = $this->createSigninForm($activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $participant = new Participant();
            $participant->setManagedActivity($activity);
            $participant->setParticipantRole($data['participantRole']);
            $participant->setPerson($data['person']);

            $em->persist($participant);
            $em->flush();

            $this->addFlash('notice', 'You have been signed in!');

            return $this->redirectToRoute('signin_show', [ 'id' => $activity->getId(), 'key' => $activity->getSigninKey() ]);
        }

        return $this->render('signin/show.html.twig', array(
            'activity' => $activity,
            'signin_form' => $form->createView(),
        ));
    }


    private function createSigninForm(Activity $activity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('signin_show', array('id' => $activity->getId(), 'key' => $activity->getSigninKey())))
            ->setMethod('POST')
            ->add('person', EntityType::class, ['class' => 'AppBundle:Person', 'choice_label' => function (Person $a) { return $a->getForename() . ' ' . $a->getSurname(); }, 'placeholder' => '' ])
            ->add('participantRole', EntityType::class, ['class' => 'AppBundle:ParticipantRole', 'choice_label' => 'role', 'label' => 'Role'])
            ->getForm()
        ;
    }
}
