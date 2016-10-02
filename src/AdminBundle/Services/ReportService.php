<?php

namespace AdminBundle\Services;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CalendarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Doctrine\ORM\EntityManager;

class ReportService
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    public function buildGenderChart($date)
    {
        //Fetch data
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);

        $females = 0;
        $males = 0;
        foreach ($members as $member) {
            if ($member->getGender() == 'F') {
                $females++;
            } elseif ($member->getGender() == 'M') {
                $males ++;
            }
        }

        //Build the chart object
        $chart = new PieChart();
        $chart->getOptions()->setTitle('Membership by gender');
        $chart->getData()->setArrayToDataTable(
            [
                ['Gender', 'Count'],
                ['Male', $males],
                ['Female', $females]
            ]
        );

        return $chart;
    }


    public function buildReturningChart($date)
    {
        //Fetch data
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);

        $new = 0;
        $returning = 0;

        foreach ($members as $member) {
            if (sizeof($member->getMemberRegistration()) > 1) {
                $returning++;
            } else {
                $new++;
            }
        }



        $chart = new PieChart();
        $chart->getOptions()->setTitle('New vs Returning');
        $chart->getData()->setArrayToDataTable(
            [
                ['Returning', 'Count'],
                ['Yes', $returning],
                ['No', $new]
            ]
        );

        return $chart;
    }


    public function buildAgeChart($date, $groupLimits)
    {
        //Fetch data
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);

        $grouper = new \AppBundle\Util\Grouper($groupLimits);

        //Assign each member to a bin based on their DOB
        foreach ($members as $member) {
            $now = new \DateTime();
            $dob = $member->getDob();

            if ($dob !== null) {
                $age = $dob->diff($now)->y;

                //Skip members with no DOB set
                if ($age == 0) {
                    continue;
                }

                $grouper->addItem($age);
            }
        }

        $data = [['Age Group', 'Count']];
        foreach ($grouper->getGroups() as $group) {
            $data[] = [ $group['name'], $group['count']];
        }

        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Membership by age');
        $chart->getData()->setArrayToDataTable($data);

        return $chart;
    }


    public function buildLengthChart($date)
    {
        $grouper = new \AppBundle\Util\Grouper([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        //Fetch data
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);
        foreach ($members as $member) {
            $now = new \DateTime();
            $joinedDate = $member->getJoinedDate();

            $length = $joinedDate->diff($now)->y;

            $grouper->addItem($length);
        }

        $data = [['Age Group', 'Count']];
        foreach ($grouper->getGroups() as $group) {
            $data[] = [ $group['name'], $group['count']];
        }


        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Membership by length of time');
        $chart->getData()->setArrayToDataTable($data);


        return $chart;
    }


    public function buildMembershipTypeChart($date)
    {
        $grouper = new \AppBundle\Util\TextGrouper();

        //Fetch data
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);
        foreach ($members as $member) {
            $membershipType = $member->getCurrentMemberRegistration()->getMembershipTypePeriod()->getMembershipType()->getType();

            $grouper->addItem($membershipType);
        }

        $data = [['Type', 'Count']];
        foreach ($grouper->getGroups() as $group) {
            $data[] = [ $group['name'], $group['count']];
        }


        $chart = new PieChart();
        $chart->getOptions()->setTitle('Membership by type');
        $chart->getData()->setArrayToDataTable($data);


        return $chart;
    }


    public function buildCalendarChart()
    {
        $activities = $this->em->getRepository('AppBundle:Activity')->findAll();


        $data = [[['label' => 'Date', 'type' => 'date'], ['label' => 'Type', 'type' => 'number'], [ 'role' => 'tooltip' ]]];

        foreach ($activities as $activity) {
            $data[] = [ $activity->getActivityStart(), $activity->getPeople(), $activity->getName() ];
        }


        $cal = new CalendarChart();
        $cal->getData()->setArrayToDataTable($data);

        return $cal;
    }


    public function buildActivityTypeChart()
    {
        $grouper = new \AppBundle\Util\TextGrouper();

        $activities = $this->em->getRepository('AppBundle:Activity')->findAll();

        $counts = [];
        foreach ($activities as $activity) {
            $grouper->addItem($activity->getActivityType()->getType(), $activity->getPeople());
        }

        $data = [['Activity Type', 'Count']];
        foreach ($grouper->getGroups() as $group) {
            $data[] = [ $group['name'], $group['count']];
        }

        $chart = new PieChart();
        $chart->getOptions()->setTitle('Visits by type');
        $chart->getData()->setArrayToDataTable($data);
        $chart->getOptions()->setWidth(350);

        return $chart;
    }
}
