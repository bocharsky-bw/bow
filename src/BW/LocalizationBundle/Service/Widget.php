<?php

namespace BW\LocalizationBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use BW\MainBundle\Service\PropertyOverload;

class Widget {
    
    /**
     * The Service Container
     * @var Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;
    
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    
    public function getLangs() {
        $data = new PropertyOverload;
        
        $data->langs = $this->container->get('doctrine.orm.entity_manager')->getRepository('BWLocalizationBundle:Lang')->findAll();
        
        return $this->container->get('templating')->render('BWLocalizationBundle:Widget:langs.html.twig', $data->toArray());
    }
    
    public function getAdminLangs() {
        $data = new PropertyOverload;
        
        $data->langs = $this->container->get('doctrine.orm.entity_manager')->getRepository('BWLocalizationBundle:Lang')->findAll();
        
        return $this->container->get('templating')->render('BWLocalizationBundle:Admin/Widget:langs.html.twig', $data->toArray());
    }
    
}