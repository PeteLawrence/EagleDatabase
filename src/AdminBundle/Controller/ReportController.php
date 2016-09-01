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
}
