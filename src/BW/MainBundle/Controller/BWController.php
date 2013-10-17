<?php

namespace BW\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BW\Helpers\PropertyOverload\PropertyOverload;

class BWController extends Controller {
    

    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\ORM\EntityManager $em Instance of EntityManager class
     * @global \Doctrine\DBAL\Connection $conn
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
}