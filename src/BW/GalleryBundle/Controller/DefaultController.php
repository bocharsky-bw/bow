<?php

namespace BW\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BWGalleryBundle:Default:index.html.twig', array('name' => $name));
    }
}
