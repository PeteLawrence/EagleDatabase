<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Participant;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AdminBundle\Services\ReportService;

/**
 * @Route("/activity")
 */
class ActivityController extends Controller
{
    /**
     * @Route("/", name="activity_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('AppBundle:Activity')->findAll();

        $days = [];
        $date = new \DateTime();
        $date->sub(new \DateInterval('P3D'));
        for ($i = 0; $i < 180; $i++) {
            $d = $date->format('d M Y');

            $days[$d] = [
                'start' => $d,
                'activities' => []
            ];
            $date->add(new \DateInterval('P1D'));
        }

        foreach ($activities as $activity) {
            $d = $activity->getActivityStart()->format('d M Y');

            if (isset($days[$d])) {
                $days[$d]['activities'][] = $activity;
            }
        }

        // replace this example code with whatever you need
        return $this->render('activity/index.html.twig', [
            'days' => $days
        ]);
    }


    /**
     * @Route("/{id}", name="activity_view")
     */
    public function viewAction(Request $request, Activity $activity)
    {

        // replace this example code with whatever you need
        return $this->render(
            'activity/view.html.twig',
            [
                'activity' => $activity,
                'google_maps_key' => $this->getParameter('site.google_maps_key')
            ]
        );
    }


    /**
     * @Route("/{id}/edit", name="activity_edit")
     */
    public function editAction(Request $request, Activity $activity)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        if ($activity instanceof UnmanagedActivity) {
            $editForm = $this->createForm('AppBundle\Form\Type\UnmanagedActivityType', $activity);
        } else {
            $editForm = $this->createForm('AppBundle\Form\Type\ManagedActivityType', $activity);
        }
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('activity_view', array('id' => $activity->getId()));
        }

        // replace this example code with whatever you need
        return $this->render(
            'activity/edit.html.twig',
            [
                'activity' => $activity,
                'edit_form' => $editForm->createView()
            ]
        );
    }


    /**
     * @Route("/{id}/contact", name="activity_contact")
     */
    public function contactAction(Request $request, Activity $activity)
    {
        //Only allow the organiser access to this page
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->get('doctrine')->getManager();

        //Build and Handle the contact form
        $contactForm = $this->buildContactForm($activity);
        $contactForm->handleRequest($request);


        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $data = $contactForm->getData();

            $message = new \AppBundle\Entity\ActivityMessage;
            $message->setActivity($activity);
            $message->setPerson($this->getUser());
            $message->setMessage($data['message']);
            $message->setSentDateTime(new \DateTime());

            $em->persist($message);
            $em->flush();

            //Send an email to the organiser
            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf('%s has contacted you about %s', $message->getPerson()->getName(), $activity->getName()))
                ->setFrom($this->getParameter('site.email'))
                ->setTo($activity->getOrganiser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activityContact.html.twig',
                        array('activity' => $activity, 'message' => $message)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            //Display confirmation flash message
            $this->addFlash('notice', 'Your message has been sent to the organiser');

            return $this->redirectToRoute('activity_view', [ 'id' => $activity->getId() ]);
        }

        return $this->render(
            'activity/contact.html.twig',
            [
                'activity' => $activity,
                'contactForm' => $contactForm->createView()
            ]
        );
    }


    /**
     * @Route("/{id}/messages", name="activity_messages")
     */
    public function messagesAction(Request $request, Activity $activity)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        return $this->render(
            'activity/messages.html.twig',
            [
                'activity' => $activity
            ]
        );
    }




    /**
     * @Route("/{id}/signup", name="activity_signup")
     */
    public function signupAction(Request $request, Activity $activity)
    {
        $em = $this->get('doctrine')->getManager();

        $signupForm = $this->buildSignupForm($activity);
        $signupForm->handleRequest($request);

        if ($signupForm->isSubmitted() && $signupForm->isValid()) {
            $data = $signupForm->getData();
            $participantStatus = $em->getRepository('AppBundle:ParticipantStatus')->findOneByStatus('Attending');

            $participant = new \AppBundle\Entity\Participant;
            $participant->setManagedActivity($activity);
            $participant->setPerson($this->get('security.token_storage')->getToken()->getUser());
            $participant->setSignupMethod('online');
            $participant->setSignupDateTime(new \DateTime());
            $participant->setParticipantStatus($participantStatus);
            $participant->setNotes($data['notes']);
            $em->persist($participant);

            //Add a charge if applicable
            if ($activity->getCost() > 0) {
                $now = new \DateTime();
                $charge = new \AppBundle\Entity\ActivityCharge;
                $charge->setManagedActivity($activity);
                $charge->setPerson($participant->getPerson());
                $charge->setDescription('Activity: ' . $activity->getName());
                $charge->setAmount($activity->getCost());
                $charge->setCreatedDateTime($now);
                $charge->setDueDateTime($now->add(new \DateInterval('P1W')));
                $em->persist($charge);
            }

            $em->flush();

            //Send an email to the organiser
            $message = \Swift_Message::newInstance()
                ->setSubject(sprintf('%s has signed up to %s', $participant->getPerson()->getName(), $activity->getName()))
                ->setFrom($this->getParameter('site.email'))
                ->setTo($activity->getOrganiser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activitySignup.html.twig',
                        array('signup' => $participant)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            //Display confirmation flash message
            $this->addFlash('notice', 'You have signed up to the activity');

            return $this->redirectToRoute('activity_view', [ 'id' => $activity->getId() ]);
        }

        // replace this example code with whatever you need
        return $this->render(
            'activity/signup.html.twig',
            [
                'activity' => $activity,
                'form' => $signupForm->createView()
            ]
        );
    }


    /**
     * @Route("/{id}/participants", name="activity_participants")
     */
    public function participantsAction(Request $request, Activity $activity)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        return $this->render(
            'activity/participants.html.twig',
            [
                'activity' => $activity
            ]
        );
    }


    /**
     * @Route("/{id}/participants/add", name="activity_participants_add")
     */
    public function participantsAddAction(Request $request, Activity $activity)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $form = $this->buildAddParticipantForm($activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $attendingStatus = $em->getRepository('AppBundle:ParticipantStatus')->findOneByStatus('attending');
            $participant = new \AppBundle\Entity\Participant();
            $participant->setSignupMethod('manual');
            $participant->setSignupDateTime(new \DateTime());
            $participant->setManagedActivity($activity);
            $participant->setPerson($data['person']);
            $participant->setParticipantStatus($attendingStatus);
            $participant->setNotes($data['notes']);

            $em->persist($participant);
            $em->flush();

            //Display a flash message
            $this->addFlash('notice', sprintf('%s has been added to %s', $participant->getPerson()->getName(), $activity->getName()));

            return $this->redirectToRoute('activity_participants', array('id' => $participant->getManagedActivity()->getId()));
        }

        return $this->render(
            'activity/participants_add.html.twig',
            [
                'activity' => $activity,
                'form' => $form->createView()
            ]
        );
    }


    /**
     * @Route("/{id}/participants/{participant}/edit", name="activity_participants_edit")
     */
    public function participantsEditAction(Request $request, Activity $activity, \AppBundle\Entity\Participant $participant)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm('AppBundle\Form\Type\Activity\ParticipantType', $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Flush changes
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            //Display a flash message
            $this->addFlash('notice', sprintf('The notes for %s have been updated', $participant->getPerson()->getName()));

            return $this->redirectToRoute('activity_participants', array('id' => $participant->getManagedActivity()->getId()));
        }


        return $this->render(
            'activity/participantsEdit.html.twig',
            [
                'activity' => $activity,
                'participant' => $participant,
                'form' => $form->createView()
            ]
        );
    }




    /**
     * @Route("/{id}/participants/{participant}/delete", name="activity_participants_delete")
     */
    public function participantsDeleteAction(Request $request, Activity $activity, \AppBundle\Entity\Participant $participant)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        $deleteForm = $this->createDeleteParticipantForm($activity, $participant);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($participant);
            $em->flush();

            //Display a flash message
            $this->addFlash('notice', sprintf('%s has been removed from %s', $participant->getPerson()->getName(), $activity->getName()));

            return $this->redirectToRoute('activity_participants', array('id' => $participant->getManagedActivity()->getId()));
        }


        return $this->render(
            'activity/participantsDelete.html.twig',
            [
                'activity' => $activity,
                'participant' => $participant,
                'deleteForm' => $deleteForm->createView()
            ]
        );
    }


    /**
     * Creates a form to delete a Activity entity.
     *
     * @param Activity $activity The Activity entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteParticipantForm(Activity $activity, Participant $participant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activity_participants_delete', array('id' => $activity->getId(), 'participant' => $participant->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * @Route("/{id}/participants/emaillist", name="activity_participants_emaillist")
     */
    public function participantsEmailListAction(Request $request, Activity $activity)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        return $this->render(
            'activity/participantsEmailList.html.twig',
            [
                'activity' => $activity
            ]
        );
    }


    /**
     * @Route("/{id}/stats", name="activity_stats")
     */
    public function statsAction(Request $request, Activity $activity, ReportService $reportService)
    {
        //Only allow the organiser access to this page
        if (
            $activity->getOrganiser() != $this->getUser() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
        ) {
            throw $this->createAccessDeniedException();
        }

        $persons = [];
        foreach ($activity->getParticipant() as $p) {
            $persons[] = $p->getPerson();
        }

        return $this->render(
            'activity/stats.html.twig',
            [
                'activity' => $activity,
                'genderPieChart' => $reportService->buildGenderPieChart($persons),
                'membershipTypePieChart' => $reportService->buildMembershipTypePieChart($persons, $activity->getActivityStart())
            ]
        );
    }


    /**
     * @Route("/{id}/weather", name="activity_weather")
     */
    public function weatherAction(Request $request, Activity $activity)
    {
        return $this->render(
            'activity/weather.html.twig',
            [
                'activity' => $activity
            ]
        );
    }


    private function buildAddParticipantForm($activity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activity_participants_add', array('id' => $activity->getId())))
            ->setMethod('POST')
            ->add('person', EntityType::class, [
                'class' => 'AppBundle:Person',
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use ($activity) {
                    return $er->queryMembersAtDate(new \DateTime());
                },
                'choice_label' => function (\AppBundle\Entity\Person $a) { return $a->getForename() . ' ' . $a->getSurname(); },
                'label' => 'Participant',
                'placeholder' => ''
            ])
            ->add('notes', TextAreaType::class, [ 'required' => false ])
            ->getForm()
        ;
    }


    private function buildContactForm($activity)
    {
        return $this->createFormBuilder()
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => '5'
                ],
                'label' => 'Message to the organiser',
                'required' => true
            ])
            //->setAction($this->generateUrl('activity_signup', array('id' => $activity->getId())))
            ->setMethod('POST')
            ->getForm()
        ;
    }


    private function buildSignupForm($activity)
    {
        return $this->createFormBuilder()
            ->add('notes', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'What sort of boat would you like to paddle? Can you transport a boat? etc.',
                    'rows' => '5'
                ],
                'label' => 'Notes to the organiser',
                'required' => false
            ])
            ->setAction($this->generateUrl('activity_signup', array('id' => $activity->getId())))
            ->setMethod('POST')
            ->getForm()
        ;
    }


}
