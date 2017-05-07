<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
        $reportService = $this->get('eagle_report');

        return $this->render('admin/report/emaillist.html.twig', [
            'regularEmailsString' => $reportService->buildRegularEmailsList(),
            'btEmailsString' => $reportService->buildBtEmailsList()
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
     * @Route("/attendancedetail", name="admin_report_attendancedetail")
     * @Method({"GET", "POST"})
     */
    public function attendanceDetailAction(Request $request)
    {
        $reportService = $this->get('eagle_report');

        $from = new \DateTime('2017-01-01');
        $to = new \DateTime('2017-12-31');

        $form = $this->buildAttendanceDetailForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->render('admin/report/attendancedetail.html.twig', array(
                'attendanceByGenderChart' => $reportService->buildAttendanceByGenderChart($data['fromDate'], $data['toDate'], $data['activityType']),
                'attendanceByGenderPieChart' => $reportService->buildAttendanceByGenderPieChart($data['fromDate'], $data['toDate'], $data['activityType']),
                'attendanceByTypeChart' => $reportService->buildAttendanceByTypeChart($data['fromDate'], $data['toDate'], $data['activityType']),
                'attendanceByTypePieChart' => $reportService->buildAttendanceByTypePieChart($data['fromDate'], $data['toDate'], $data['activityType']),
                'attendanceBySignupMethodPieChart' => $reportService->buildAttendanceBySignupMethodPieChart($data['fromDate'], $data['toDate'], $data['activityType']),
                'form' => $form->createView()
            ));
        }


        return $this->render('admin/report/attendancedetail.html.twig', array(
            'form' => $form->createView()
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
     * Database Engagement Report
     *
     * @Route("/databaseengagement", name="admin_report_databaseengagement")
     * @Method({"GET", "POST"})
     */
    public function databaseEngagementAction(Request $request)
    {
        $reportService = $this->get('eagle_report');

        return $this->render('admin/report/databaseengagement.html.twig', array(
            'accountStatusPieChart' => $reportService->buildAccountStatusPieChart(),
            'usedOnlineSignupPieChart' => $reportService->buildUsedOnlineSignUpPieChart(),
            'onlineSignupCountChart' => $reportService->buildOnlineSignupCountChart()
        ));

    }


    /**
     * Displays a map of members addresses
     *
     * @Route("/membermap", name="admin_report_membermap")
     * @Method({"GET", "POST"})
     */
    public function memberMapAction(Request $request)
    {
        $reportService = $this->get('eagle_report');

        return $this->render('admin/report/membermap.html.twig', array(
            'memberMap' => $reportService->buildMemberMap(),
            'google_maps_key' => $this->getParameter('site.google_maps_key')
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
                'memberCount' => sizeof($reportService->getActiveMembers()),
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


    /**
     * Lists Qualifications
     *
     * @Route("/qualifications", name="admin_report_qualifications")
     * @Method({"GET", "POST"})
     */
    public function qualificationsAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        $form = $this->buildQualificationsForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $qualifications = $em->getRepository('AppBundle:MemberQualification')->findByQualification($data['qualification']);

            return $this->render('admin/report/qualifications.html.twig', array(
                'form' => $form->createView(),
                'qualifications' => $qualifications
            ));
        } else {
            return $this->render('admin/report/qualifications.html.twig', array(
                'form' => $form->createView()
            ));
        }
    }


    /**
     * Lists youth members
     *
     * @Route("/youth", name="admin_report_youth")
     * @Method("GET")
     */
    public function youthAction()
    {
        $em = $this->get('doctrine')->getManager();

        $youth = $em->getRepository('AppBundle:MembershipType')->findOneByType('Youth');
        $cadet = $em->getRepository('AppBundle:MembershipType')->findOneByType('Cadet');

        $people = $em->getRepository('AppBundle:Person')->findMembersByType([$youth, $cadet]);

        return $this->render('admin/report/youth.html.twig', array(
            'people' => $people
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


    private function buildAttendanceDetailForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->add('fromDate', DateType::class, [
                'data' => new \DateTime('first day of january'),
                'widget' => 'single_text'
            ])
            ->add('toDate', DateType::class, [
                'data' => new \DateTime('last day of december'),
                'widget' => 'single_text'
            ])
            ->add('activityType', EntityType::class, [
                'class' => 'AppBundle:ActivityType',
                'choice_label' => function (\AppBundle\Entity\ActivityType $at) { return $at->getType(); },
                'placeholder' => 'All',
                'required' => false
            ])
            ->getForm()
        ;
    }


    private function buildQualificationsForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->add('qualification', EntityType::class, [
                'class' => 'AppBundle:Qualification',
                'choice_label' => function (\AppBundle\Entity\Qualification $q) { return $q->getName(); },
                'placeholder' => '',
                'required' => false
            ])
            ->getForm()
        ;
    }
}
