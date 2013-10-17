<?php

namespace BW\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Form\PostType;
use BW\BlogBundle\Entity\Post;

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
        
        $data->posts = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->findBy(
            array(
            ),
            array(
                'created' => 'desc',
            )
        );
        
        return $this->render('BWBlogBundle:Admin/Post:posts.html.twig', $data->toArray());
    }
    
    public function postAction() {
        
    }
    
    public function addAction() {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        $post = new Post;
        $form = $this->createForm(new \BW\BlogBundle\Form\PostAddType(), $post);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($post);
                $em->flush();
                
                if ( $form->get('saveAndExit')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_posts') );
                }
                
                return $this->redirect( $this->generateUrl('admin_edit_post', array('id' => $post->getId())) );
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWBlogBundle:Admin/Post:add-post.html.twig', $data->toArray());
    }
    
    public function editAction($id) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        $post = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->find($id);
        $form = $this->createForm(new \BW\BlogBundle\Form\PostEditType(), $post);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ( $form->get('delete')->isClicked() ) {
                    $em->remove($post);
                    $em->flush();
                    
                    return $this->redirect( $this->generateUrl('admin_posts') );
                }
                
                $em->persist($post);
                $em->flush();
                
                if ( $form->get('saveAndExit')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_posts') );
                }
                
                return $this->redirect( $this->generateUrl('admin_edit_post', array('id' => $post->getId())) );
            }
        }
        
        $data->post = $post;
        $data->form = $form->createView();
        return $this->render('BWBlogBundle:Admin/Post:edit-post.html.twig', $data->toArray());
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
