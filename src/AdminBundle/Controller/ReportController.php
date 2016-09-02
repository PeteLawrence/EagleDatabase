<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Activity;
use AppBundle\Entity\ManagedActivity;
use AppBundle\Entity\UnmanagedActivity;
use AppBundle\Entity\Participant;
use AppBundle\Form\ManagedActivityType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

/**
 * Activity controller.
 *
 * @Route("/report")
 */
class ReportController extends Controller
{
    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="report_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('admin/report/index.html.twig');
    }

    /**
     * Lists all Activity entities.
     *
     * @Route("/emaillist", name="report_emaillist")
     * @Method("GET")
     */
    public function emaillistAction()
    {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository('AppBundle:Person')->findAll();
        $emails = [];
        foreach ($people as $person) {
            if ($person->getEmail() != '') {
                $emails[] = $person->getEmail();
            }
        }
        $emailsString = implode('; ', $emails);

        return $this->render('admin/report/emaillist.html.twig', [
            'emailsString' => $emailsString
        ]);
    }

    /**
     * Lists all Activity entities.
     *
     * @Route("/attendance", name="report_attendance")
     * @Method("GET")
     */
    public function attendanceAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data[] = ['Date', 'People'];

        $activities = $em->getRepository('AppBundle:Activity')->findAll();
        foreach ($activities as $activity) {
            $data[] = [ $activity->getActivityStart(), $activity->getPeople() ];
        }

        $chart = new ColumnChart();
        $chart->getData()->setArrayToDataTable($data);
        $chart->getOptions()->setIsStacked(true);
        $chart->getOptions()->getBar()->setGroupWidth('20%');

        return $this->render('admin/report/attendance.html.twig', array(
            'chart' => $chart,
        ));
    }


    /**
     * Lists all Activity entities.
     *
     * @Route("/overview", name="report_overview")
     * @Method("GET")
     */
    public function overviewAction()
    {
        return $this->render('admin/report/overview.html.twig', array(
            'members' => 120,
            'coaches' => 21,
            'genderChart' => $this->buildGenderChart(),
            'ageChart' => $this->buildAgeChart(),
            'lengthChart' => $this->buildLengthChart(),
            'visitsChart' => $this->buildVisitsChart(),
            'qualificationChart' => $this->buildQualificationChart()
        ));
    }


    private function buildGenderChart()
    {
        $chart = new PieChart();
        $chart->getOptions()->setTitle('Membership by gender');
        $chart->getData()->setArrayToDataTable(
            [
                ['Gender', 'Count'],
                ['Male', 75],
                ['Female', 45]
            ]
        );

        return $chart;
    }


    private function buildAgeChart()
    {
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Membership by age');
        $chart->getData()->setArrayToDataTable(
            [
                ['Age Group', 'Count'],
                ['0-11',  4],
                ['11-18',  8],
                ['18-25',  16],
                ['25-35',  14],
                ['35-45',  22],
                ['45-55', 20],
                ['55+',  12],
            ]
        );

        return $chart;
    }


    private function buildLengthChart()
    {
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Membership by length of time');
        $chart->getData()->setArrayToDataTable(
            [
                ['Age Group', 'Count'],
                ['0-1',  38],
                ['1-2',  25],
                ['2-3',  18],
                ['3-4',  16],
                ['4-5',  15],
                ['5-6', 12],
                ['6-7',  6],
                ['7-8',  0],
                ['8-9',  2],
            ]
        );

        return $chart;
    }

    private function buildVisitsChart()
    {
        $chart = new PieChart();
        $chart->getOptions()->setTitle('Visits');
        //$chart->getOptions()->setIsStacked(true);
        $chart->getData()->setArrayToDataTable(
            [
                ['Type', 'Visits'],
                ['Wednesday Night', 1002],
                ['Friday Night', 78],
                ['Whitewater Weekend', 98],
                ['Touring Weekend', 87],
                ['Pool Session', 124]
            ]
        );
        /*$chart->getData()->setArrayToDataTable(
            [
                ['Year', 'Wednesday Night', 'Friday Night Paddle', 'Whitewater weekend', 'Touring weekend'],
                ['2014', 1002, 129, 42, 45],
                ['2015', 1043, 132, 89, 75],
                ['2016', 1098, 120, 143, 98]
            ]
        );*/

        return $chart;
    }


    private function buildQualificationChart()
    {
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Qualifications');
        //$chart->getOptions()->setIsStacked(true);
        $chart->getData()->setArrayToDataTable(
            [
                ['Type', 'Members'],
                ['Level 1 Coach', 12],
                ['Level 2 Coach', 7],
                ['Level 3 Coach', 3],
                ['FSRT', 38],
                ['WWSRT', 17],
                ['First Aid (1 day)', 42],
                ['First Aid (2 day)', 20],
            ]
        );

        return $chart;
    }
}
