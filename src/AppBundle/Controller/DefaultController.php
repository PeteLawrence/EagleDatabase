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
     * @Route("/faqs", name="faqs")
     */
    public function faqsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/faqs.html.twig');
    }

}
