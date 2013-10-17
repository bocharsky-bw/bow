<?php

namespace BW\MainBundle\Controller;

use BW\MainBundle\Controller\BWController;

class MainController extends BWController
{

    /**
     * Конструктор MainController
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     */
    public function __construct() {
        
        parent::__construct();
        
    }
    
    public function indexAction() {
        
        return $this->render('BWMainBundle:Main:index.html.twig');
    }
    
}
