<?php

namespace AdminBundle\Services;

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


    public function buildAgeChart($date)
    {
        //Fetch data
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);

        $grouper = new \AppBundle\Util\Grouper([18, 25, 35, 45, 55, 65]);

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
        $grouper = new \AppBundle\Util\Grouper([1, 2, 3, 4, 5, 6]);

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
}
