<?php

namespace BW\MenuBundle\Controller\Admin;

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
    

    public function menusAction() {
        $data = $this->getPropertyOverload();
        
        $data->menus = $this->getDoctrine()->getRepository('BWMenuBundle:Menu')->findBy(
            array(
                
            )
        );
        
        return $this->render('BWMenuBundle:Admin/Menu:menus.html.twig', $data->toArray());
    }
    
    public function menuAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $menu = $this->getDoctrine()->getRepository('BWMenuBundle:Menu')->find($id);
        } else {
            $menu = new Menu();
        }
        
        $form = $this->createForm(new MenuType(), $menu);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($form->get('delete')->isClicked()) {
                    $em->remove($menu);
                    $em->flush();
                    
                    return $this->redirect($this->generateUrl('admin_menus'));
                }
                
                $em->flush();
                
                if ($form->get('saveAndClose')->isClicked()) {
                    return $this->redirect($this->generateUrl('admin_menus'));
                }
                
                return $this->redirect($this->generateUrl('admin_menu_edit', array('id' => $menu->getId())));
            }
        }
        
        $data->form = $form->createView();
        if ($id) {
            return $this->render('BWMenuBundle:Admin/Menu:menu-edit.html.twig', $data->toArray());
        }
        
        return $this->render('BWMenuBundle:Admin/Menu:menu-add.html.twig', $data->toArray());
    }
    
    public function deleteAction($id) {
        
    }
}
