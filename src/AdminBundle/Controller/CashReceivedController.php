<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Activity controller.
 *
 * @Route("/cashreceived")
 */
class CashReceivedController extends Controller
{
    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="admin_cashreceived_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        $form = $this->buildCashReceivedForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $payments = $em->getRepository('AppBundle:Charge')->findPaymentsBetween($data['fromDate'], $data['toDate']);

            return $this->render('admin/cashreceived/index.html.twig', [
                'form' => $form->createView(),
                'payments' => $payments
            ]);
        }

        return $this->render('admin/cashreceived/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function buildCashReceivedForm()
    {
        return $this->createFormBuilder()
            ->setMethod('POST')
            ->add('fromDate', DateType::class, ['widget' => 'single_text', 'data' => new \DateTime('first day of january'), 'format' => 'dd-MM-yyyy'])
            ->add('toDate', DateType::class, ['widget' => 'single_text', 'data' => new \DateTime('tomorrow'), 'format' => 'dd-MM-yyyy'])
            ->add('submit', SubmitType::class, ['attr' => ['class' => 'btn btn-primary']])
            ->getForm()
        ;
    }

}
