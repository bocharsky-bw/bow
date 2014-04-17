<?php

namespace BW\UserBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Description of AuthenticationSuccessHandler
 *
 * @author BrainForce 3.0
 */
class AuthSuccessHandler implements AuthenticationSuccessHandlerInterface {
    
    /**
     * The Service Container object
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
    
    
    /**
     * Update last user visit date when user auth successfull
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        if ($token->getUser()) {
            $token->getUser()->setUpdated(new \DateTime);
            $this->container->get('doctrine.orm.entity_manager')->flush();
        }
        
        return new RedirectResponse($this->container->get('router')->generate('home'));
    }
    
}
