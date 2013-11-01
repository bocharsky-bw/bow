<?php

namespace BW\LocalizationBundle\Controller;

use BW\MainBundle\Controller\BWController;

class LangController extends BWController
{

    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    public function __construct() {
        parent::__construct();
    }


    public function langsMenuAction(\Symfony\Component\HttpFoundation\Request $request) {
        $data = $this->getPropertyOverload();
        //$request = $this->get('request');
        
        $data->request = $request;
        $data->langs = $this->getDoctrine()->getRepository('BWLocalizationBundle:Lang')->findAll();
        
        return $this->render('BWLocalizationBundle:Lang:langs-menu.html.twig', $data->toArray());
    }
    
}
