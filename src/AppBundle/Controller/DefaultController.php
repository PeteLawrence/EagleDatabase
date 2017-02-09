<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }


    /**
     * @Route("/centre", name="centre")
     */
    public function centreAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/centre.html.twig');
    }


    /**
     * @Route("/howtojoin", name="howtojoin")
     */
    public function howtojoinAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/howtojoin.html.twig');
    }


    /**
     * @Route("/faqs", name="faqs")
     */
    public function faqsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/faqs.html.twig');
    }


    /**
     * @Route("/whoswho", name="whoswho")
     */
    public function whoswhoAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/whoswho.html.twig');
    }


    /**
     * @Route("/grantsandawards", name="grantsandawards")
     */
    public function grantsandawardsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/grantsandawards.html.twig');
    }


    /**
     * @Route("/docsanddownloads", name="docsanddownloads")
     */
    public function docsanddownloadsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/docsanddownloads.html.twig');
    }


    /**
     * @Route("/programme", name="programme")
     */
    public function programmeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $activities = $em->getRepository('AppBundle:Activity')->findAll();

        $days = [];
        $date = new \DateTime();
        for ($i = 0; $i < 120; $i++) {
            $d = $date->format('d M Y');
            
            $days[$d] = [
                'start' => $d,
                'activities' => []
            ];
            $date->add(new \DateInterval('P1D'));
        }

        foreach ($activities as $activity) {
            $d = $activity->getActivityStart()->format('d M Y');

            if (isset($days[$d])) {
                $days[$d]['activities'][] = $activity;
            }
        }


        dump($days);
        // replace this example code with whatever you need
        return $this->render('default/programme.html.twig', [
            'days' => $days
        ]);
    }
}
