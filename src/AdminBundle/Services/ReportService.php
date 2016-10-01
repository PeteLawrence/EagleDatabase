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

        //Define bins
        $bins = [
            [ 'max' => 12, 'count' => 0 ],
            [ 'max' => 18, 'count' => 0 ],
            [ 'max' => 25, 'count' => 0 ],
            [ 'max' => 35, 'count' => 0 ],
            [ 'max' => 45, 'count' => 0 ],
            [ 'max' => 55, 'count' => 0 ],
            [ 'max' => 65, 'count' => 0 ]
        ];

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

                $binCount = sizeof($bins);
                for ($i = 0; $i < $binCount; $i++) {
                    if ($age < $bins[$i]['max']) {
                        $bins[$i]['count']++;
                        continue 2;
                    }
                }
            }
        }

        $data = [['Age Group', 'Count']];
        foreach ($bins as $bin) {
            $data[] = [ '<' . $bin['max'], $bin['count']];
        }

        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Membership by age');
        $chart->getData()->setArrayToDataTable($data);

        return $chart;
    }
}
