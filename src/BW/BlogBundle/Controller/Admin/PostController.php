<?php

namespace BW\BlogBundle\Controller\Admin;

use BW\BlogBundle\Entity\PostCustomField;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Entity\Post;
use BW\BlogBundle\Form\PostType;

class PostController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Post $post
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function postsAction() {
        $data = $this->getPropertyOverload();
        
        $data->posts = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->findAll();
        
        return $this->render('BWBlogBundle:Admin/Post:posts.html.twig', $data->toArray());
    }
    
    public function postAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        $request->getSession()->set('AllowCKFinder', TRUE); // Allow to use CKFinder
        
        if ($id) {
            $post = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->find($id);
        } else {
            $post = new Post;
        }

        $form = $this->createForm(new PostType(), $post);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($post);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Страница успешно удалена из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_posts') );
                    }
                }

                if ($form->get('add_custom_field')->isClicked()) {
                    $customField = $form->get('custom_field')->getData();
                    if ($customField) {
                        $postCustomField = new PostCustomField();
                        $postCustomField->setPost($post);
                        $postCustomField->setCustomField($customField);
                        $em->persist($postCustomField);
                    }
                }
                
                if ( ! $post->getId()) {
                    $em->persist($post);
                    $em->flush(); // сохраняем в БД чтобы получить ID элемента для роута
                }
                $route = $post->getRoute();
                if ( ! $route) {
                    $route = new \BW\RouterBundle\Entity\Route();
                    $post->setRoute($route);
                    $em->persist($route);
                    $em->flush();
                }
                
                $this->get('bw_blog.transliter')->generateSlug($post);
                
                $segments = array();
                $parent = $post->getCategory();
                while ($parent) {
                    $segments[] = $parent->getSlug();
                    $parent = $parent->getParent();
                }
                $query = ($segments ? implode('/', array_reverse($segments)) .'/' : '') . $post->getSlug();
                $route->setPath(($post->getLang() ? $post->getLang().'/' : '') . $query);
                $route->setQuery($query);
                $route->setLang($post->getLang());
                $route->setDefaults(array(
                    '_controller' => 'BWBlogBundle:Post:post',
                    'id' => $post->getId(),
                ));
                
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Страница успешно сохранена в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_posts') );
                }
                
                return $this->redirect( $this->generateUrl('admin_post_edit', array('id' => $post->getId())) );
            }
        }
        
        $data->post = $post;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/Post:edit-post.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/Post:add-post.html.twig', $data->toArray());
    }
    
    public function deleteAction() {
        
    }
    
    public function togglePublishedAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $post = $em->getRepository('BWBlogBundle:Post')->find($id);
        $post->setPublished( ! $post->getPublished() );
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('admin_posts'));
    }
}
