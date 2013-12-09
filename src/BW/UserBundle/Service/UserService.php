<?php

namespace BW\UserBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of UserBlockService
 *
 * @author BrainForce 3.0
 */
class UserService {
    
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
    
    
    public function getSignInBlock() {
        
        return $this->container->get('templating')->render('BWUserBundle:User:sign-in-block.html.twig');
    }
    
}
