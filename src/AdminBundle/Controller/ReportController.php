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
use AdminBundle\Services\ReportService;

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
     * @Method({"GET", "POST"})
     */
    public function emaillistAction(Request $request, ReportService $reportService)
    {
        $form = $this->buildMembershipTypeForm();
        $form->handleRequest($request);

        $emailsString = '';

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $emailsString = $reportService->buildEmailsList($data['membershipType']);
        }

        return $this->render('admin/report/emaillist.html.twig', [
            'form' => $form->createView(),
            'emailsString' => $emailsString,
        ]);
    }



    private function buildMembershipTypeForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->add('membershipType', EntityType::class, [
                'class' => 'AppBundle:MembershipType',
                'choice_label' => function (\AppBundle\Entity\MembershipType $at) { return $at->getType(); },
                'multiple' => true,
                'placeholder' => 'All',
                'required' => false
            ])
            ->getForm()
        ;
    }


    /**
     * Lists Active Members
     *
     * @Route("/activemembers", name="admin_report_activemembers")
     * @Method("GET")
     */
    public function activeMembersAction(ReportService $reportService)
    {
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
    public function attendanceAction(ReportService $reportService)
    {
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
    public function attendanceDetailAction(Request $request, ReportService $reportService)
    {
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
    public function attendanceLeagueAction(Request $request, ReportService $reportService)
    {
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
     * Lists all Activity entities.
     *
     * @Route("/bcaffiliation", name="admin_report_bcaffiliation")
     * @Method({"GET", "POST"})
     */
    public function bcAffiliationAction(Request $request, ReportService $reportService)
    {
        return $this->render('admin/report/bcaffiliation.html.twig', array(
            'data' => $reportService->getBCAffiliationData(),
            'volunteerData' => $reportService->getBCAffiliationVolunteerData(),
            'disabledData' => $reportService->getBCAffiliationDisabledData()
        ));
    }


    /**
     * Database Engagement Report
     *
     * @Route("/databaseengagement", name="admin_report_databaseengagement")
     * @Method({"GET", "POST"})
     */
    public function databaseEngagementAction(Request $request, ReportService $reportService)
    {
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
    public function memberMapAction(Request $request, ReportService $reportService)
    {
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
    public function membershipAction(Request $request, ReportService $reportService)
    {
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
     * @Route("/enrolment", name="admin_report_enrolment")
     * @Method("GET")
     */
    public function enrolmentAction(ReportService $reportService)
    {
        return $this->render('admin/report/enrolment.html.twig', array(
            'enrolmentByTypeChart' => $reportService->buildEnrolmentByTypeChart(),
            'enrolmentByGenderChart' => $reportService->buildEnrolmentByGenderChart()
        ));
    }



    /**
     * Displays figures required for the grant bodies
     *
     * @Route("/grants", name="admin_report_grants")
     * @Method({"GET", "POST"})
     */
    public function grantsAction(Request $request, ReportService $reportService)
    {
        $form = $this->buildGrantsForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $membershipData = $reportService->getGrantsMembershipData($data['membershipPeriod']);
            $participationData = $reportService->getGrantsParticipationData($data['membershipPeriod']);
            $coachingData = $reportService->getGrantsCoachingData($data['membershipPeriod']);

            return $this->render('admin/report/grants.html.twig', array(
                'form' => $form->createView(),
                'membership' => $membershipData,
                'participation' => $participationData,
                'coaching' => $coachingData
            ));
        }

        return $this->render('admin/report/grants.html.twig', array(
            'form' => $form->createView()
        ));

    }


    private function buildGrantsForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->add('membershipPeriod', EntityType::class, [
                'class' => 'AppBundle:MembershipPeriod',
                'choice_label' => function (\AppBundle\Entity\MembershipPeriod $mp) { return $mp->getName(); },
                'placeholder' => '',
                'required' => true
            ])
            ->getForm()
        ;
    }



    /**
     * Displays figures required for the grant bodies
     *
     * @Route("/participationgrid", name="admin_report_participationgrid")
     * @Method({"GET", "POST"})
     */
    public function participationGridAction(Request $request, ReportService $reportService)
    {
        $timeline = $reportService->buildParticipationGridTimeline();


        return $this->render('admin/report/participationgrid.html.twig', array(
            'timeline' => $timeline
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

            $qualifications = $em->getRepository('AppBundle:MemberQualification')->findMemberQualificationsByType($data['qualification']);

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
     * Lists Expiring Qualifications
     *
     * @Route("/expiringqualifications", name="admin_report_expiringqualifications")
     * @Method({"GET", "POST"})
     */
    public function expiringQualificationsAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $expiringQualifications = $em->getRepository('AppBundle:MemberQualification')->findExpiringQualifications();

        return $this->render('admin/report/expiringQualifications.html.twig', array(
            'expiringQualifications' => $expiringQualifications
        ));
    }


    /**
     * Lists Expired Qualifications
     *
     * @Route("/expiredqualifications", name="admin_report_expiredqualifications")
     * @Method({"GET", "POST"})
     */
    public function expiredQualificationsAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();
        $expiredQualifications = $em->getRepository('AppBundle:MemberQualification')->findExpiredQualifications();

        return $this->render('admin/report/expiredQualifications.html.twig', array(
            'expiredQualifications' => $expiredQualifications
        ));
    }


        /**
         * Lists Qualifications Awaiting Verification
         *
         * @Route("/qualificationsrequiringverification", name="admin_report_qualificationsrequiringverification")
         * @Method({"GET", "POST"})
         */
        public function qualificationsRequiringVerificationAction(Request $request)
        {
            $em = $this->get('doctrine')->getManager();
            $qualifications = $em->getRepository('AppBundle:MemberQualification')->finddQualificationsRequiringVerification();

            return $this->render('admin/report/qualificationsRequiringVerification.html.twig', array(
                'qualifications' => $qualifications
            ));
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
