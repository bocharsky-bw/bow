<?php

namespace BW\MenuBundle\Service;

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
    
    
    public function getMenu($alias) {
        $data = new PropertyOverload;
        
        $data->menu = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository('BWMenuBundle:Menu')
                ->findOneBy(array(
                    'alias' => $alias,
                )
            );
        $data->items = $this->container
                ->get('doctrine.orm.entity_manager')
                ->getRepository('BWMenuBundle:Item')
                ->findBy(array(
                    'menu' => $data->menu,
                    'lang' => $this->container->get('bw_localization.lang')->getLang(),
                )
            );
        
        return $this->container->get('templating')->render('BWMenuBundle:Widget:menu.html.twig', $data->toArray());
    }
    
}