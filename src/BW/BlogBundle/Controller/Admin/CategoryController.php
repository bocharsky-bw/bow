<?php

namespace BW\BlogBundle\Controller\Admin;

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
                
                $route = $post->getRoute();
                if ( ! $route) {
                    $route = new \BW\RouterBundle\Entity\Route();
                    $em->persist($route);
                }
                $route->setPath( $post->getLang() .'/cms/'. $post->getSlug() );
                $route->setDefaults(array(
                    '_controller' => 'BWBlogBundle:Post:post',
                    'slug' => $post->getSlug(),
                ));
                $post->setRoute($route);
                
                $em->persist($post);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Страница успешно сохранена в БД'
                    );
                
                if ( $form->get('saveAndExit')->isClicked() ) {
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
