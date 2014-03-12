<?php

namespace BW\BreadcrumbsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;

class Widget {
    
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
    
    public function generateFromPost(\BW\BlogBundle\Entity\Post $post) {
        $links = array();
        
        $links[] = array(
            'heading' => $post->getHeading(),
            'query' => $post->getRoute()->getQuery(),
        );
        if ($post->getCategory()) {
            $links[] = array(
                'heading' => $post->getCategory()->getHeading(),
                'query' => $post->getCategory()->getRoute()->getQuery(),
            );
            $parent = $post->getCategory(); // for recursive call
            while ($parent = $parent->getParent()) {
                $links[] = array(
                    'heading' => $parent->getHeading(),
                    'query' => $parent->getRoute()->getQuery(),
                );
            }
        }
        
        $links = array_reverse($links); // Разворачиваем массив ссылок для правильного порядка
        $this->html = $this->templating->render('BWMainBundle:Breadcrumbs:index.html.twig', array(
            'links' => $links,
        ));
        
        return $this->html;
    }
    
    public function generateFromCategory(\BW\BlogBundle\Entity\Category $category) {
        $links = array();

        $links[] = array(
            'heading' => $category->getHeading(),
            'query' => $category->getRoute()->getQuery(),
        );
        $parent = $category; // for recursive call
        while ($parent = $parent->getParent()) {
            $links[] = array(
                'heading' => $parent->getHeading(),
                'query' => $parent->getRoute()->getQuery(),
            );
        }
        
        $links = array_reverse($links); // Разворачиваем массив ссылок для правильного порядка
        $this->html = $this->templating->render('BWMainBundle:Breadcrumbs:index.html.twig', array(
            'links' => $links,
        ));
        
        return $this->html;
    }
    
}