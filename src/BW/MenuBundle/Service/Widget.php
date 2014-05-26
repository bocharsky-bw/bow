<?php

namespace BW\MenuBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use BW\MainBundle\Service\PropertyOverload;

/**
 * Class Widget
 * @package BW\MenuBundle\Service
 */
class Widget {
    
    /**
     * The Service Container
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;


    /**
     * The constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    /**
     * Render HTML code of menu by alias
     *
     * @param string $alias The menu alias
     * @param bool $display_heading Whether display heading or no
     *
     * @return string
     */
    public function getMenu($alias, $display_heading = true) {
        $data = new PropertyOverload;
        
        $data->display_heading = $display_heading;
        $data->menu = $this->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository('BWMenuBundle:Menu')
            ->findOneBy(array(
                'alias' => $alias,
            ))
        ;
        $data->items = $this->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository('BWMenuBundle:Item')
            ->findBy(array(
                'menu' => $data->menu,
                'lang' => $this->container->get('bw_localization.lang')->getLang(),
            ))
        ;
        $data->nestedNodes = $this->container
            ->get('bw_blog.nested_set')
            ->generateNestedNodesFromEntities($data->items)
        ;
        
        return $this->container
            ->get('templating')
            ->render('BWMenuBundle:Widget:menu.html.twig', $data->toArray())
        ;
    }
    
}