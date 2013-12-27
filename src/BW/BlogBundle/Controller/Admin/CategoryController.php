<?php

namespace BW\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Entity\Category;
use BW\BlogBundle\Entity\Post;
use BW\BlogBundle\Form\CategoryType;
use BW\BlogBundle\Form\PostType;

class CategoryController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Post $post
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function categoriesAction() {
        $data = $this->getPropertyOverload();
        
        $data->categories = $this->getDoctrine()->getRepository('BWBlogBundle:Category')->findAll();
        
        return $this->render('BWBlogBundle:Admin/Category:categories.html.twig', $data->toArray());
    }
    
    public function categoryAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $category = $this->getDoctrine()->getRepository('BWBlogBundle:Category')->find($id);
        } else {
            $category = new Category;
        }
        
        $form = $this->createForm(new CategoryType(), $category);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($category);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Элемент успешно удален из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_categories') );
                    }
                }
                
                if ( ! $category->getId()) {
                    $em->persist($category);
                    $em->flush(); // сохраняем в БД чтобы получить ID элемента для роута
                }
                $route = $category->getRoute();
                if ( ! $route) {
                    $route = new \BW\RouterBundle\Entity\Route();
                    $em->persist($route);
                }
                $route->setPath( $category->getLang() .'/cms/'. $category->getSlug() );
                $route->setQuery( 'cms/'. $category->getSlug() );
                $route->setLang( $category->getLang() );                
                $route->setDefaults(array(
                    '_controller' => 'BWBlogBundle:Category:category',
                    'id' => $category->getId(),
                ));
                $category->setRoute($route);
                
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Страница успешно сохранена в БД'
                    );
                
                if ( $form->get('saveAndExit')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_categories') );
                }
                
                return $this->redirect( $this->generateUrl('admin_category_edit', array('id' => $category->getId())) );
            }
        }
        
        $data->category = $category;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/Category:edit-category.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/Category:add-category.html.twig', $data->toArray());
    }
    
    public function deleteAction() {
        
    }
    
    public function togglePublishedAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $category = $em->getRepository('BWBlogBundle:Category')->find($id);
        $category->setPublished( ! $category->getPublished() );
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('admin_categories'));
    }
}
