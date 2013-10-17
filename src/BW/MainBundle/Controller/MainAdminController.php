<?php

namespace BW\MainBundle\Controller;

use BW\MainBundle\Controller\BWController;

class MainAdminController extends BWController
{

    /**
     * Конструктор MainAdminController
     * @global \Doctrine\DBAL\Connection $conn
     */
    public function __construct() {
        
        parent::__construct();
        
    }
    
    public function indexAction() {
        
        return $this->render('BWMainBundle:MainAdmin:index.html.twig');
    }
}
