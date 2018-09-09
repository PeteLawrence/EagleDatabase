<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

/**
 * Activity controller.
 *
 * @Route("/charge")
 */
class ChargeController extends Controller
{
    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="admin_charge_index", methods={"GET"})
     */
    public function indexAction()
    {
        return $this->render('admin/charge/index.html.twig');
    }

    /**
     * Lists all Activity entities.
     *
     * @Route("/due", name="admin_charge_due", methods={"GET"})
     */
    public function chargesDueAction()
    {
        $em = $this->getDoctrine()->getManager();

        $charges = $em->getRepository('AppBundle:Charge')->findDuePayments();

        //Total the charges due
        $totalDue = 0;
        foreach ($charges as $charge) {
            $totalDue += $charge->getAmount();
        }

        return $this->render('admin/charge/due.html.twig', [
            'charges' => $charges,
            'totalDue' => $totalDue
        ]);
    }


    /**
     * Lists all Activity entities.
     *
     * @Route("/overdue", name="admin_charge_overdue", methods={"GET"})
     */
    public function chargesOverdueAction()
    {
        $em = $this->getDoctrine()->getManager();

        $charges = $em->getRepository('AppBundle:Charge')->findOverduePayments();

        //Total the charges due
        $totalOverdue = 0;
        foreach ($charges as $charge) {
            $totalOverdue += $charge->getAmount();
        }

        return $this->render('admin/charge/overdue.html.twig', [
                'charges' => $charges,
                'totalOverdue' => $totalOverdue
            ]);
    }
}
