<?php

namespace AppBundle\Services;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\MemberRegistration;

class MembershipService
{
    private $em;
    private $formFactory;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\Form\FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    public function getAvailableMembershipTypes()
    {
        $membershipTypePeriods = $this->em->getRepository('AppBundle:MembershipTypePeriod')->findAll();
        $now = new \DateTime();

        $options = [];
        foreach ($membershipTypePeriods as $mtp) {
            $mp = $mtp->getMembershipPeriod();
            if (($mp->getSignupFromDate() < $now) && ($mp->getSignupToDate() > $now)) {
                $options[] = $mtp;
            }
        }

        return $options;
    }


    public function buildMembershipTypeForm()
    {
        return $this->formFactory->createBuilder()
            ->setMethod('POST')
            ->add('membershipTypePeriod',
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


    /*
     * Given a user, return the next applicable MembershipTypePeriod
     */
    public function getNextMembershipTypePeriod($person)
    {
        $currentMemberRegistration = $person->getCurrentMemberRegistration();

        // Handle members without an active membership
        if (!$currentMemberRegistration) {
            return null;
        }

        $currentMembershipType = $currentMemberRegistration->getMembershipTypePeriod()->getMembershipType();

        $membershipTypePeriods = $this->em->getRepository('AppBundle:MembershipTypePeriod')->findAll();
        $now = new \DateTime();
        foreach ($membershipTypePeriods as $mtp) {
            $mp = $mtp->getMembershipPeriod();
            if (($mp->getSignupFromDate() < $now) && ($mp->getSignupToDate() > $now) && $mtp->getMembershipType() == $currentMembershipType && $mtp != $currentMemberRegistration->getMembershipTypePeriod()) {
                return $mtp;
            }
        }

        return null;
    }


    public function buildMembershipExtrasForm($membershipTypePeriod, $includeAdminExtras = true)
    {
        $fb = $this->formFactory->createBuilder()
            ->setMethod('POST');

        //Loop over each of the extras available given $membershipTypePeriod
        foreach ($membershipTypePeriod->getMembershipTypePeriodExtra() as $extra) {

            // Don't include the extra if its admin only
            if (!$includeAdminExtras && $extra->getMembershipExtra()->getAdminSelectableOnly()) {
                continue;
            }

            $fb->add(
                'extra_' . $extra->getId(),
                CheckboxType::class,
                [
                    'label' => sprintf(
                        '£%s - %s',
                        number_format($extra->getValue(), 2),
                        $extra->getMembershipExtra()->getName()
                    ),
                    'help' => $extra->getMembershipExtra()->getDescription(),
                    'required' => false
                ]
            );
        }

        return $fb->getForm();
    }


    public function buildMembershipRegistration($membershipTypePeriodId, $membershipTypePeriodExtraIds, $member)
    {
        $membershipTypePeriod = $this->em->getRepository('AppBundle:MembershipTypePeriod')->findOneById($membershipTypePeriodId);
        $memberRegistration = new MemberRegistration();
        $memberRegistration->setMembershipTypePeriod($membershipTypePeriod);
        $memberRegistration->setRegistrationDateTime(new \DateTime());
        $memberRegistration->setPerson($member);

        foreach ($membershipTypePeriodExtraIds as $extraId) {
            $extra = $this->em->getRepository('AppBundle:MembershipTypePeriodExtra')->findOneById($extraId);
            $membershipRegistrationExtra = new \AppBundle\Entity\MemberRegistrationExtra;
            $membershipRegistrationExtra->setMembershipTypePeriodExtra($extra);
            $membershipRegistrationExtra->setMemberRegistration($memberRegistration);
            $memberRegistration->addMemberRegistrationExtra($membershipRegistrationExtra);
        }

        return $memberRegistration;
    }


    public function clearSessionEntries($session)
    {
        $session->remove('enrol_person');
        $session->remove('enrol_mtp');
        $session->remove('enrol_extras');
        $session->remove('renew_mtp');
        $session->remove('renew_extras');
    }


    public function buildMemberRegistrationCharge($memberRegistration, $amount, $paid)
    {
        $memberRegistrationCharge = new \AppBundle\Entity\MemberRegistrationCharge();
        $memberRegistrationCharge->setDescription('Membership');
        $memberRegistrationCharge->setAmount($amount);
        $memberRegistrationCharge->setReference('');
        $memberRegistrationCharge->setPaid(false);
        $memberRegistrationCharge->setDuedatetime(new \DateTime());
        $memberRegistrationCharge->setCreateddatetime(new \DateTime());
        $memberRegistrationCharge->setMemberRegistration($memberRegistration);
        $memberRegistrationCharge->setPerson($memberRegistration->getPerson());

        return $memberRegistrationCharge;
    }


    public function buildConfirmForm()
    {
        return $this->formFactory->createBuilder()
            ->setMethod('POST')
            ->add('confirm', CheckboxType::class, [ 'label' => 'I confirm that the above details are correct'])
            ->getForm();
    }


    public function canPersonRenew($person)
    {
        //Get current membership type
        $currentMembershipType = $person->getCurrentMemberRegistration();
        if (!$currentMembershipType) { //not currently a member, so cannot renew
            return false;
        }

        //Is there an active MembershipTypePeriod given $currentMembershipType?
        $nextMembershipTypePeriod = $this->getNextMembershipTypePeriod($person);
        if (!$nextMembershipTypePeriod) {
            return false;
        }

        //Have they already renewed?
        if ($this->hasPersonRenewed($person)) {
            return false;
        }

        return true;
    }


    public function hasPersonRenewed($person)
    {
        $nextMembershipTypePeriod = $this->getNextMembershipTypePeriod($person);  //the next applicable MembershipTypePeriod
        if (!$nextMembershipTypePeriod) {
            return false;
        }

        //
        foreach ($person->getMemberRegistration() as $r) {
            if ($r->getMembershipTypePeriod() == $nextMembershipTypePeriod) {
                return true;
            }
        }

        return false;
    }
}
