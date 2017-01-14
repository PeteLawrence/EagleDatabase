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
}
