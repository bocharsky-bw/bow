<?php

namespace BW\MainBundle\Controller;

use BW\MainBundle\Controller\BWController;

class SocialController extends BWController
{

    /**
     * Конструктор SocialController
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function signInAction() {
        
        return $this->render('BWMainBundle:Main:index.html.twig');
    }
    
}
