<?php

namespace BW\SliderBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Widget
 * @package BW\SliderBundle\Service
 */
class Widget {
    
    /**
     * The Service Container
     *
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;


    /**
     * The constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * Render HTML code of slider by alias
     *
     * @param $alias The slider alias
     *
     * @return string
     */
    public function create($alias)
    {
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

    /**
     * Render HTML code of slider's group by alias
     *
     * @param $alias The slider group alias
     *
     * @return string
     */
    public function createGroup($alias)
    {
        $group = $this->container->get('doctrine')
            ->getRepository('BWSliderBundle:Group')
            ->findOneBy(
                array(
                    'alias' => $alias,
                )
            )
        ;

        return $this->container->get('templating')->render('BWSliderBundle:Widget:slider-group.html.twig', array(
            'group' => $group,
        ));
    }
}