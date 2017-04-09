<?php

namespace AdminBundle\Services;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\CalendarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Map;
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


    public function buildMemberMap()
    {
        $grouper = new \AppBundle\Util\TextGrouper();
        $now = new \DateTime();

        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($now);

        foreach ($members as $member) {
            //Split out the outgoing part of the postcode
            $postcodeParts = explode(' ', $member->getPostcode());
            $outgoingPart = $postcodeParts[0];

            $grouper->addItem($member->getPostcode());
        }

        $data = [['Location', 'Name'] ];
        foreach($grouper->getGroups() as $group) {
            $data[] = [ $group['name'] . ', UK', sprintf('%s: %s', $group['name'], $group['count']) ];
        }

        $map = new Map();
        $map->getData()->setArrayToDataTable($data);
        $map->getOptions()->setShowTip(true);
        $map->getOptions()->setUseMapTypeControl(true);

        return $map;
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


    public function getActiveMembers()
    {
        $now = new \DateTime();

        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($now);

        return $members;
    }


    public function buildCalendarChart()
    {
        $activities = $this->em->getRepository('AppBundle:ManagedActivity')->findAll();


        $data = [[['label' => 'Date', 'type' => 'date'], ['label' => 'Type', 'type' => 'number'], [ 'role' => 'tooltip' ]]];

        foreach ($activities as $activity) {
            $tooltip = sprintf('%s - %s', $activity->getName(), sizeof($activity->getParticipant()));
            $data[] = [ $activity->getActivityStart(), $activity->getPeople(), $tooltip];
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



    public function getAttendanceLeague($fromDate, $toDate)
    {
        $q = $this->em->createQuery('SELECT person.forename, person.surname, COUNT(pa.id) AS c FROM AppBundle:Person person JOIN person.participant pa JOIN pa.managedActivity ma WHERE ma.activityStart BETWEEN ?1 AND ?2 GROUP BY person.id ORDER BY c DESC');
        $q->setParameter(1, $fromDate);
        $q->setParameter(2, $toDate);

        $rows = [];

        foreach ($q->getResult() as $result) {
            $rows[$result['forename'] . ' ' . $result['surname']] = $result['c'];
        }

        return $rows;
    }



    public function buildEnrolmentByTypeChart()
    {
        $registrations = $this->em->getRepository('AppBundle:MemberRegistration')->findAll();

        $counts = [];
        $types = [];
        $months = [];

        $groupBy = 'M Y';


        foreach ($registrations as $registration) {
            $type = $registration->getMembershipTypePeriod()->getMembershipType()->getType();
            if (!in_array($type, $types)) {
                $types[] = $type;
            }

            $month = $registration->getRegistrationDateTime()->format($groupBy);
            if (!in_array($month, $months)) {
                $months[] = $month;
            }
        }

        //Build empty data array
        foreach ($months as $month) {
            $r = [];
            foreach ($types as $type) {
                $r[$type] = 0;
            }
            $counts[$month] = $r;
        }

        //Populate the data array
        foreach ($registrations as $registration) {
            $month = $registration->getRegistrationDateTime()->format($groupBy);
            $type = $registration->getMembershipTypePeriod()->getMembershipType()->getType();

            $counts[$month][$type]++;
        }


        //Turn the data array into a Google Charts Data table
        $r = ['Month'];
        foreach ($types as $type) {
            $r[] = $type;
        }
        $data = [
            $r
        ];

        foreach ($counts as $month => $counts) {
            $row = [$month];
            foreach ($types as $type) {
                $row[] = $counts[$type];
            }
            $data[] = $row;
        }

        //Build the chart object
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Enrolments by Type');
        $chart->getOptions()->setIsStacked(true);
        $chart->getOptions()->setHeight('300');
        $chart->getData()->setArrayToDataTable($data);

        return $chart;
    }



    public function buildEnrolmentByGenderChart()
    {
        $registrations = $this->em->getRepository('AppBundle:MemberRegistration')->findAll();

        $counts = [];
        $genders = ['F', 'M'];
        $months = [];

        $groupBy = 'M Y';


        foreach ($registrations as $registration) {
            $month = $registration->getRegistrationDateTime()->format($groupBy);
            if (!in_array($month, $months)) {
                $months[] = $month;
            }
        }

        //Build empty data array
        foreach ($months as $month) {
            $counts[$month] = ['F' => 0, 'M' => 0];
        }

        //Populate the data array
        foreach ($registrations as $registration) {
            $month = $registration->getRegistrationDateTime()->format($groupBy);
            $gender = $registration->getPerson()->getGender();

            $counts[$month][$gender]++;
        }


        //Turn the data array into a Google Charts Data table
        $data = [
            ['Month', 'Female', 'Male']
        ];

        foreach ($counts as $month => $counts) {
            $row = [$month, $counts['F'], $counts['M']];
            $data[] = $row;
        }

        //Build the chart object
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Enrolments by Gender');
        $chart->getOptions()->setIsStacked(true);
        $chart->getOptions()->setHeight('300');
        $chart->getData()->setArrayToDataTable($data);

        return $chart;
    }
}
