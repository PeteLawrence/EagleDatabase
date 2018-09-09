<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function indexAction()
    {
        return $this->render('admin/index.html.twig');
    }
}
