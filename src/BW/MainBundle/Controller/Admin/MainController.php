<?php

namespace BW\MainBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;

class MainController extends BWController
{

    /**
     * Конструктор MainController
     * @global \Doctrine\DBAL\Connection $conn
     */
    public function __construct() {
        
        parent::__construct();
        
    }
    
    public function indexAction() {
        
        return $this->render('BWMainBundle:Admin/Main:index.html.twig');
    }
    
    public function configAction() {
        
        return $this->render('BWMainBundle:Admin/Main:configuration.html.twig');
    }
}
