<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;

class CategoryController extends BWController
{
    
    public function categoryAction($id) {
        $data = $this->getPropertyOverload();
        
        $data->category = $this->getDoctrine()->getRepository('BWBlogBundle:Category')->find(array(
            'id' => $id,
        ));
        
        return $this->render('BWBlogBundle:Category:category.html.twig', $data->toArray());
    }
}
