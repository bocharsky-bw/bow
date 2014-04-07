<?php

namespace BW\SliderBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Widget {
    
    /**
     * The Service Container
     * @var Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;

    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    
    public function create($alias) {
        $slider = $this->container->get('doctrine')
                ->getRepository('BWSliderBundle:Slider')
                ->findOneBy(
                    array(
                        'alias' => $alias,
                    )
                )
            ;
        
        return $this->container->get('templating')->render('BWSliderBundle:Widget:slider.html.twig', array(
            'slider' => $slider,
        ));
    }
}