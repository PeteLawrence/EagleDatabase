<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
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
     * @Route("/", name="admin_report_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('admin/report/index.html.twig');
    }

    /**
     * Lists all Activity entities.
     *
     * @Route("/emaillist", name="admin_report_emaillist")
     * @Method("GET")
     */
    public function emaillistAction()
    {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository('AppBundle:Person')->findMembersAtDate(new \DateTime());
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
         * Lists Active Members
         *
         * @Route("/activemembers", name="admin_report_activemembers")
         * @Method("GET")
         */
        public function activeMembersAction()
        {
            $reportService = $this->get('eagle_report');


            return $this->render('admin/report/activemembers.html.twig', array(
                'activeMembers' => $reportService->getActiveMembers()
            ));
        }

    /**
     * Lists all Activity entities.
     *
     * @Route("/attendance", name="admin_report_attendance")
     * @Method("GET")
     */
    public function attendanceAction()
    {
        $reportService = $this->get('eagle_report');


        return $this->render('admin/report/attendance.html.twig', array(
            'calendarChart' => $reportService->buildCalendarChart(),
            'activityTypeChart' => $reportService->buildActivityTypeChart()
        ));
    }


    /**
     * Lists all Activity entities.
     *
     * @Route("/attendanceleague", name="admin_report_attendanceleague")
     * @Method({"GET", "POST"})
     */
    public function attendanceLeagueAction(Request $request)
    {
        $reportService = $this->get('eagle_report');

        $form = $this->createForm(\AppBundle\Form\Type\DateRangeSelectorType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->render('admin/report/attendanceleague.html.twig', array(
                'form' => $form->createView(),
                'attendance' => $reportService->getAttendanceLeague($data['fromDate'], $data['toDate'])
            ));
        }


        return $this->render('admin/report/attendanceleague.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a membership overview page
     *
     * @Route("/membership", name="admin_report_membership")
     * @Method({"GET", "POST"})
     */
    public function membershipAction(Request $request)
    {
        $reportService = $this->get('eagle_report');

        $form = $this->createForm(\AppBundle\Form\Type\MembershipReportFilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->render('admin/report/membership.html.twig', array(
                'form' => $form->createView(),
                'genderChart' => $reportService->buildGenderChart($data['date']),
                'ageChart' => $reportService->buildAgeChart($data['date'], str_getcsv($data['ageRanges'])),
                'returningChart' => $reportService->buildReturningChart($data['date']),
                'lengthChart' => $reportService->buildLengthChart($data['date']),
                'membershipTypeChart' => $reportService->buildMembershipTypeChart($data['date'])
            ));
        } else {
            return $this->render('admin/report/membership.html.twig', array(
                'form' => $form->createView()
            ));
        }
    }


    /**
     * Lists all Activity entities.
     *
     * @Route("/overview", name="admin_report_overview")
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
            'returningChart' => $this->buildReturningChart(),
            'visitsChart' => $this->buildVisitsChart(),
            'visitsByGenderChart' => $this->buildVisitsByGenderChart(),
            'qualificationChart' => $this->buildQualificationChart(),
            'whiteWaterGenderChart' => $this->buildWhiteWaterGenderChart(),
            'whiteWaterAgeChart' => $this->buildWhiteWaterAgeChart()
        ));
    }


    /**
     * Lists all Activity entities.
     *
     * @Route("/nextofkin", name="admin_report_nextofkin")
     * @Method("GET")
     */
    public function nextOfKinAction()
    {
        $em = $this->get('doctrine')->getManager();

        $people = $em->getRepository('AppBundle:Person')->findMembersAtDate(new \DateTime());

        return $this->render('admin/report/nextOfKin.html.twig', array(
            'people' => $people
        ));
    }


    /**
     * Lists all Activity entities.
     *
     * @Route("/enrolment", name="admin_report_enrolment")
     * @Method("GET")
     */
    public function enrolmentAction()
    {
        $reportService = $this->get('eagle_report');

        return $this->render('admin/report/enrolment.html.twig', array(
            'enrolmentByTypeChart' => $reportService->buildEnrolmentByTypeChart(),
            'enrolmentByGenderChart' => $reportService->buildEnrolmentByGenderChart()
        ));
    }



    private function buildVisitsByGenderChart()
    {
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Visits by gender');
        $chart->getOptions()->setIsStacked('relative');
        $chart->getData()->setArrayToDataTable(
                [
                    ['Type', 'Male', 'Female'],
                    ['Wednesday Night', 600, 389],
                    ['Friday Night', 40, 38],
                    ['Whitewater Weekend', 70, 28],
                    ['Touring Weekend', 50, 46],
                    ['Pool Session', 64, 61],
                    ['Sea Trip', 8, 9],
                    ['Lee Valley', 18, 2],
                    ['Horstead', 26, 14],
                ]
            );

        $chart->getOptions()->setWidth(730);

        return $chart;
    }


    private function buildQualificationChart()
    {
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Qualifications');
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


    private function buildWhiteWaterGenderChart()
    {
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Attendance');
        $chart->getOptions()->setIsStacked(true);
        $chart->getData()->setArrayToDataTable(
            [
                ['Date', 'Female', 'Male', [ 'role' =>  'annotation' ] ],
                [new \DateTime('2016-05-28'), 12, 23, 'Symmonds Yat'],
                [new \DateTime('2016-07-12'), 7, 17, 'Dee'],
                [new \DateTime('2016-08-16'), 6, 12, 'Tryweryn'],
                [new \DateTime('2016-11-28'), 5, 11, 'Dart Loop'],
            ]
        );

        return $chart;
    }


    private function buildWhiteWaterAgeChart()
    {
        $chart = new ColumnChart();
        $chart->getOptions()->setTitle('Attendance');
        $chart->getOptions()->setIsStacked(true);
        $chart->getData()->setArrayToDataTable(
            [
                ['Date', '10-18', '18-25', '25-35', '35-45', '45-55', '55-65', '65+', [ 'role' =>  'annotation' ] ],
                [new \DateTime('2016-05-28'), 3, 7, 6, 4, 6, 2, 1, 'Symmonds Yat'],
                [new \DateTime('2016-07-12'), 2, 9, 3, 2, 3, 0, 0, 'Dee'],
                [new \DateTime('2016-08-16'), 1, 5, 4, 2, 2, 1, 0, 'Tryweryn'],
                [new \DateTime('2016-11-28'), 0, 3, 3, 2, 1, 0, 0, 'Dart Loop'],
            ]
        );

        return $chart;
    }
}
