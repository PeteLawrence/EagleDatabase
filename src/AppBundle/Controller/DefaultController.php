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
        $em = $this->get('doctrine')->getManager();

        $upcomingActivities = $em->getRepository('AppBundle:ManagedActivity')->findNextActivities(3);


        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'upcomingActivities' => $upcomingActivities
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
     * @Route("/kitlist", name="kitlist")
     */
    public function kitListAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/kitList.html.twig');
    }


    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function newslettersAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/newsletters.html.twig');
    }


    /**
     * @Route("/picsandvideos", name="picsandvideos")
     */
    public function picsandvideosAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/picsandvideos.html.twig');
    }


    /**
     * @Route("/whatson", name="whatson")
     */
    public function whatsonAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/whatson.html.twig');
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

}
