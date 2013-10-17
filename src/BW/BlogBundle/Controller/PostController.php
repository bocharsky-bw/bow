<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;

class PostController extends BWController
{
    
    public function postAction($slug) {
        $data = $this->getPropertyOverload();
        
        $data->post = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->findOneBy(
            array(
                'slug' => $slug,
                'published' => TRUE,
            )
        );
        
        if ( ! $data->post) {
            throw $this->createNotFoundException('Статья по адресу "'. $slug .'" не найдена.');
        }
        
        return $this->render('BWBlogBundle:Post:post.html.twig', $data->toArray());
    }
}
