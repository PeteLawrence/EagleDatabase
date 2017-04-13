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


    public function buildGenderPieChart($persons)
    {
        $females = 0;
        $males = 0;
        foreach ($persons as $person) {
            if ($person->getGender() == 'F') {
                $females++;
            } elseif ($person->getGender() == 'M') {
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


    public function buildGenderChart($date)
    {
        //Fetch data
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);

        return $this->buildGenderPieChart($members);
    }



    public function buildAttendanceByGenderChart($from, $to, $activityType)
    {
        $activities = $this->em->getRepository('AppBundle:ManagedActivity')->findActivitiesBetweenDates($from, $to, $activityType);

        $counts = [['Name', ['role' => 'tooltip'], 'Males', 'Females']];
        foreach ($activities as $activity) {
            $males = 0;
            $females = 0;
            foreach ($activity->getParticipant() as $p) {
                if ($p->getPerson()) {
                    $gender = $p->getPerson()->getGender();
                    if ($gender == 'M') {
                        $males++;
                    } else {
                        $females++;
                    }
                }
            }

            $counts[] = [$activity->getActivityStart(), $activity->getName(), $males, $females];
        }

        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Attendance by Gender');
        $chart->getOptions()->setIsStacked(true);
        $chart->getOptions()->setHeight('300');
        //$chart->getOptions()->getBar()->setGroupWidth('95%');
        //$chart->getOptions()->getExplorer()->setAxis('horizontal');
        //$chart->getOptions()->getExplorer()->setKeepInBounds(true);
        //$chart->getOptions()->getExplorer()->setMaxZoomIn(0.1);
        $chart->getData()->setArrayToDataTable($counts);

        return $chart;
    }


    public function buildAttendanceByGenderPieChart($from, $to, $activityType)
    {
        $activities = $this->em->getRepository('AppBundle:ManagedActivity')->findActivitiesBetweenDates($from, $to, $activityType);

        $males = 0;
        $females = 0;
        foreach ($activities as $activity) {
            foreach ($activity->getParticipant() as $p) {
                if ($p->getPerson()) {
                    $gender = $p->getPerson()->getGender();
                    if ($gender == 'M') {
                        $males++;
                    } else {
                        $females++;
                    }
                }
            }

            $counts[] = [$activity->getActivityStart(), $activity->getName(), $males, $females];
        }

        $data = [
            ['Gender', 'Count' ],
            ['Male', $males],
            ['Female', $females]
        ];

        $chart = new PieChart();
        $chart->getOptions()->setTitle('Attendance by Gender');
        $chart->getOptions()->setHeight('300');
        $chart->getData()->setArrayToDataTable($data);

        return $chart;
    }


    public function buildAttendanceByTypeChart($from, $to, $activityType)
    {
        $membershipTypes = $this->em->getRepository('AppBundle:MembershipType')->findAll();
        $activities = $this->em->getRepository('AppBundle:ManagedActivity')->findActivitiesBetweenDates($from, $to, $activityType);

        $headers = ['Name', ['role' => 'tooltip']];
        foreach ($membershipTypes as $mt) {
            $headers[] = $mt->getType();
        }

        $counts = [ $headers ];
        foreach ($activities as $activity) {
            $row = [$activity->getActivityStart(), $activity->getName()];

            //Add in 0 counts for each member type
            for ($i = 0; $i < sizeof($membershipTypes); $i++) {
                $row[] = 0;
            }

            foreach ($activity->getParticipant() as $p) {

                if ($p->getPerson() && $p->getPerson()->getCurrentMemberRegistration()) {
                    $type = $p->getPerson()->getCurrentMemberRegistration()->getMembershipTypePeriod()->getMembershipType()->getType();
                    $pos = array_search($type, $headers);
                    $row[$pos]++;
                }

            }
            $counts[] = $row;
        }

        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Attendance by Type');
        $chart->getOptions()->setIsStacked(true);
        $chart->getOptions()->setHeight('300');
        //$chart->getOptions()->getBar()->setGroupWidth('95%');
        $chart->getOptions()->getExplorer()->setAxis('horizontal');
        $chart->getOptions()->getExplorer()->setKeepInBounds(true);
        $chart->getOptions()->getExplorer()->setMaxZoomIn(0.1);
        $chart->getData()->setArrayToDataTable($counts);

        return $chart;
    }




    public function buildAttendanceByTypePieChart($from, $to, $activityType)
    {
        $membershipTypes = $this->em->getRepository('AppBundle:MembershipType')->findAll();
        $activities = $this->em->getRepository('AppBundle:ManagedActivity')->findActivitiesBetweenDates($from, $to, $activityType);

        $counts = [];
        foreach ($activities as $activity) {
            foreach ($activity->getParticipant() as $p) {

                if ($p->getPerson() && $p->getPerson()->getCurrentMemberRegistration()) {
                    $type = $p->getPerson()->getCurrentMemberRegistration()->getMembershipTypePeriod()->getMembershipType()->getType();
                    if (isset($counts[$type])) {
                        $counts[$type]++;
                    } else {
                        $counts[$type] = 0;
                    }
                }
            }
        }

        $data = [[ 'Type', 'Count']];

        foreach ($counts as $type => $count) {
            $data[] = [$type, $count];
        }

        $chart = new PieChart();
        $chart->getOptions()->setTitle('Attendance by Type');
        $chart->getOptions()->setHeight('300');
        $chart->getData()->setArrayToDataTable($data);

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


    public function buildMembershipTypePieChart($persons)
    {
        $grouper = new \AppBundle\Util\TextGrouper();

        //Fetch data

        foreach ($persons as $person) {
            $membershipType = $person->getCurrentMemberRegistration()->getMembershipTypePeriod()->getMembershipType()->getType();

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

    public function buildMembershipTypeChart($date)
    {
        $members = $this->em->getRepository('AppBundle:Person')->findMembersAtDate($date);

        return $this->buildMembershipTypeChart($members);
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
        $cal->getOptions()->setHeight(400);

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
