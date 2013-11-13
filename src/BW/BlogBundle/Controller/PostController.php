<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;

class PostController extends BWController
{
    
    public function postsAction() {
        $data = $this->getPropertyOverload();
        
        $lang = $this->getDoctrine()->getRepository('BWLocalizationBundle:Lang')->findOneBy(array('sign' => $this->getRequest()->getLocale()));
        $type = $this->getDoctrine()->getRepository('BWBlogBundle:PostType')->findOneBy(array('alias' => 'article'));
        
        $data->posts = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->findBy(
            array(
                'published' => TRUE,
                'lang' => $lang,
                'type' => $type,
            ),
            array(
                'created' => 'desc',
            )
        );
        
        return $this->render('BWBlogBundle:Post:posts.html.twig', $data->toArray());
    }
    
    public function postAction($slug) {
        $data = $this->getPropertyOverload();
        
        $lang = $this->getDoctrine()->getRepository('BWLocalizationBundle:Lang')->findOneBy(array('sign' => $this->getRequest()->getLocale()));
        
        $data->post = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->findOneBy(
            array(
                'slug' => $slug,
                'published' => TRUE,
                'lang' => $lang,
            )
        );
        
        if ( ! $data->post) {
            throw $this->createNotFoundException('Статья по адресу "'. $slug .'" не найдена.');
        }
        
        return $this->render('BWBlogBundle:Post:post.html.twig', $data->toArray());
    }
}
