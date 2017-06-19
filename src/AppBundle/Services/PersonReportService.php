<?php

namespace AppBundle\Services;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Timeline;
use AppBundle\Entity\MemberRegistration;

class PersonReportService
{
    private $em;
    private $formFactory;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\Form\FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    public function buildParticipationTimeline($person)
    {
        $currentMembershipPeriod = $person->getCurrentMemberRegistration()->getMembershipTypePeriod()->getMembershipPeriod();

        $rows = [];
        foreach ($person->getParticipant() as $p) {
            if (
                $p->getManagedActivity()->getActivityStart() <= $currentMembershipPeriod->getFromDate() &&
                $p->getManagedActivity()->getActivityStart() < $currentMembershipPeriod->getToDate()
            ) {
                continue;
            }

            $rows[] = [
                'Participation',
                $p->getManagedActivity()->getName(),
                $p->getManagedActivity()->getActivityStart(),
                $p->getManagedActivity()->getActivityEnd()
            ];
        }


        $timeline = new Timeline();
        $timeline->getData()->setArrayToDataTable($rows, true);

        return $timeline;
    }

}
