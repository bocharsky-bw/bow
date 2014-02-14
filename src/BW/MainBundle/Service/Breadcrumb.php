<?php

namespace BW\MainBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;

class Breadcrumb {
    
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;
    
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;
    
    /**
     * @var \Symfony\Component\Templating\EngineInterface 
     */
    private $templating;
    
    /**
     * @var string Html код навигатора сайта
     */
    private $html;
    
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->request = $container->get('request');
        $this->templating = $container->get('templating');
    }
    
    
    public function __toString() {
        
        return (string)$this->html;
    }
    
    public function generateFromEntity($entity) {
        $this->html = $this->templating->render('BWMainBundle:Breadcrumb:index.html.twig', array(
            'entity' => $entity,
        ));
        
        return $this->html;
    }
    
}