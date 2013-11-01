<?php

namespace BW\MenuBundle\Controller;

use BW\MainBundle\Controller\BWController;
use BW\MenuBundle\Entity\Menu;
use BW\MenuBundle\Form\MenuType;

class MenuController extends BWController
{
    
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Symfony\Component\Form\Form $form
     * @global \Doctrine\ORM\EntityManager $em
     */
    public function __construct() {
        parent::__construct();
    }
    

    public function menuAction($id) {
        $data = $this->getPropertyOverload();
        
        $menu = $this->getDoctrine()->getRepository('BWMenuBundle:Menu')->find($id);
        $items = $menu->getItems()->toArray();
        
        $recursion = new \BW\MenuBundle\Service\Recursion();
        $data->items = $recursion->levelParentEntityRecursion($items);
        
        $data->menu = $menu;
        return $this->render('BWMenuBundle:Menu:list-menu.html.twig', $data->toArray());
    }
    
}