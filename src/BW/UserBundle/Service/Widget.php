<?php

namespace BW\UserBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of UserBlockService
 *
 * @author BrainForce 3.0
 */
class Widget {
    
    /**
     * Service Container
     * 
     * @var ContainerInterface
     */
    protected $container;
    

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->_init();
    }
    private function _init() {
    }
    
    
    public function getSignIn() {
        
        return $this->container->get('templating')->render('BWUserBundle:Widget:sign-in.html.twig');
    }
    
}
