<?php

namespace BW\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Entity\Page;
use BW\BlogBundle\Form\PageType;

class PageController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Page $page
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function pagesAction() {
        $data = $this->getPropertyOverload();
        
        $data->pages = $this->getDoctrine()->getRepository('BWBlogBundle:Page')->findAll();
        
        return $this->render('BWBlogBundle:Admin/Page:pages.html.twig', $data->toArray());
    }
    
    public function pageAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $page = $this->getDoctrine()->getRepository('BWBlogBundle:Page')->find($id);
        } else {
            $page = new Page;
        }
        
        $form = $this->createForm(new PageType(), $page);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($page);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Страница успешно удалена из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_pages') );
                    }
                }
                
                $em->persist($page);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Страница успешно сохранена в БД'
                    );
                
                if ( $form->get('saveAndExit')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_pages') );
                }
                
                return $this->redirect( $this->generateUrl('admin_page_edit', array('id' => $page->getId())) );
            }
        }
        
        $data->page = $page;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/Page:edit-page.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/Page:add-page.html.twig', $data->toArray());
    }
    
    public function deleteAction() {
        
    }
    
    public function togglePublishedAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $page = $em->getRepository('BWBlogBundle:Page')->find($id);
        $page->setPublished( ! $page->getPublished() );
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('admin_pages'));
    }
}
