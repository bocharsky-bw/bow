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
     * @global \Doctrine\DBAL\Connection $conn
     * @global \BW\BlogBundle\Entity\Post $post
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function categoriesAction() {
        $data = $this->getPropertyOverload();
        
        $data->categories = $this->getDoctrine()
                ->getRepository('BWBlogBundle:Category')
                ->findBy(array(
                ), array(
                    'left' => 'ASC',
                ))
            ;
        
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
                
                $this->get('bw_blog.transliter')->generateSlug($category);
                
                $segments = array();
                $parent = $category;
                while ($parent) {
                    $segments[] = $parent->getSlug();
                    $parent = $parent->getParent();
                }
                $query = implode('/', array_reverse($segments));
                
                $route->setPath( ($category->getLang() ? $category->getLang() .'/' : '') . $query );
                $route->setQuery( $query );
                $route->setLang( $category->getLang() );                
                $route->setDefaults(array(
                    '_controller' => 'BWBlogBundle:Category:category',
                    'id' => $category->getId(),
                ));
                $category->setRoute($route);

                $em->flush();

                // Сгенерировать и упорядочить дерево Nested Set
                $this->get('bw_blog.nested_set')->regenerateTree(
                    $em->getClassMetadata('BWBlogBundle:Category')->getTableName() // Имя таблицы класса
                );

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Страница успешно сохранена в БД'
                );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
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
    
    /* Nested Set */
//    public function getLeft($category) {
//        $conn = $this->get('database_connection');
//        
//        $left = 1; // По умолчанию lft = 1
//        if ($category->getParent()) {
//            $left = $conn->fetchColumn("SELECT MAX(c.lft) 
//                FROM categories AS c 
//                WHERE 1=1 
//                    AND c.id != :id
//                    AND c.parent_id = :parent_id 
//                    AND c.level = :level 
//            ", array(
//                'id' => $category->getId(),
//                'parent_id' => $category->getParent()->getId(),
//                'level' => $category->getLevel(),
//            ));
//            if ( ! $left) {
//                $left = $category->getParent()->getLeft();
//            }
//        } else {
//            $left = $conn->fetchColumn("SELECT MAX(c.rgt) 
//                FROM categories AS c 
//                WHERE 1=1 
//                    AND c.id != :id
//                    AND c.parent_id IS NULL
//                    AND c.level = :level 
//            ", array(
//                'id' => $category->getId(),
//                'level' => $category->getLevel(),
//            ));
//        }
//        //var_dump($left); die;
//        
//        return $left + 1;
//    }
//    
//    public function getRight($category) {
//        $conn = $this->get('database_connection');
//        
//        $right = $category->getLeft() + 1; // по умолчанию rgt на единицу больше lft
//        if ( ! $category->getChildren()->isEmpty()) {
//            $right = $conn->fetchColumn("SELECT MAX(c.rgt) 
//                FROM categories AS c 
//                WHERE 1=1 
//                    AND c.level = :level 
//                    AND c.parent_id = :parent_id 
//            ", array(
//                'level' => $category->getLevel() + 1,
//                'parent_id' => $category->getId(),
//            ));
//        }
//        //var_dump($right); die;
//        
//        return $right + 1;
//    }
    /* /Nested Set */
}
