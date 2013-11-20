<?php

namespace BW\BlockBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use BW\MainBundle\Service\PropertyOverload;

/**
 * Description of Block
 *
 * @author Victor
 */
class BlockService {
    
    /**
     * Service Container Object
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;
    
    /**
     * Request Object
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;
    
    /**
     * Twig Object
     * @var \Twig_Environment
     */
    private $twig;
    
    /**
     * Entity Manager Object
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        $this->init();
    }
    
    private function init() {
        $this->request  = $this->container->get('request');
        $this->twig     = $this->container->get('twig');
        $this->em       = $this->container->get('doctrine.orm.entity_manager');
    }

    
    /**
     * Custom HTML Block from template
     * @param string $template
     * @return string rendered template
     */
    public function html($template) {
        $template = (string)$template;

        return $this->twig->render('BWBlockBundle:Html:'. $template);
    }

    /**
     * Menu Block by ID
     * @param integer $id
     * @param string $heading
     * @return string rendered template
     */
    public function menu($alias, $heading = NULL) {
        $alias = (string)$alias;
        
        $data = new PropertyOverload;
        $data->heading = $heading;
        
        $lang = $this->container->get('bw.localization.lang')->findLangByLocale();
        $menu = $this->em->getRepository('BWMenuBundle:Menu')->findOneBy(array('alias' => $alias));
        
        if ( ! $menu) {
            return "Меню с алиасом \"$alias\" не найдено";
        }
        
        $items = $this->em->getRepository('BWMenuBundle:Item')->findBy(
            array(
                'menu' => $menu,
                'lang' => $lang,
            ),
            array(
                'ordering' => 'asc',
            )
        );
        
        $recursion = new \BW\MenuBundle\Service\Recursion();
        $data->items = $recursion->levelParentEntityRecursion($items);
        $data->menu = $menu;
        
        return $this->twig->render('BWBlockBundle:Menu:list-menu.html.twig', $data->toArray());
    }
    
    /**
     * List of langs Block
     * @return string rendered template
     */
    public function langs() {
        $data = new PropertyOverload;

        $data->langs = $this->em->getRepository('BWLocalizationBundle:Lang')->findAll();
        
        return $this->twig->render('BWBlockBundle:Lang:langs-menu.html.twig', $data->toArray());
    }
    
    /**
     * Authorization form Block
     * @return string rendered template
     */
    public function signInForm() {
        $data = new PropertyOverload;
        
        return $this->twig->render('BWBlockBundle:User:sign-in-form.html.twig', $data->toArray());
    }
}