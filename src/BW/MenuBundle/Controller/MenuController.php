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
    
    
//    public function menuAction(\Symfony\Component\HttpFoundation\Request $request, $id, $heading = NULL) {
//        $data = $this->getPropertyOverload();
//        $data->heading = $heading;
//        $data->request = $request;
//        
//        $menu = $this->getDoctrine()->getRepository('BWMenuBundle:Menu')->find($id);
//        $lang = $this->get('bw.localization.lang')->getCurrentLangEntity();
//        $items = $this->getDoctrine()->getRepository('BWMenuBundle:Item')->findBy(
//            array(
//                'menu' => $menu,
//                'lang' => $lang,
//            ),
//            array(
//                'ordering' => 'asc',
//            )
//        );
//        
//        $recursion = new \BW\MenuBundle\Service\Recursion();
//        $data->items = $recursion->levelParentEntityRecursion($items);
//        
//        $data->menu = $menu;
//        return $this->render('BWMenuBundle:Menu:list-menu.html.twig', $data->toArray());
//    }
    
}