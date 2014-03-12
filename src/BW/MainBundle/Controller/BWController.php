<?php

namespace BW\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BW\MainBundle\Service\PropertyOverload;

class BWController extends Controller {
    

    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     * @global \Doctrine\ORM\EntityManager $em Instance of EntityManager class
     */
    public function __construct() {
    }
    
    
    /**
     * Get instance of PropertyOverload class for dynemic properties
     * @return \BW\Helpers\PropertyOverload\PropertyOverload
     */
    public function getPropertyOverload() {

        return new PropertyOverload();
    }
    
    public function render($view, array $parameters = array(), \Symfony\Component\HttpFoundation\Response $response = null) {
        
        return parent::render($view, $parameters, $response);
    }
}