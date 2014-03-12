<?php

namespace BW\BreadcrumbsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BWBreadcrumbsBundle:Default:index.html.twig', array('name' => $name));
    }
}
