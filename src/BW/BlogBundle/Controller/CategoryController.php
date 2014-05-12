<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BW\MainBundle\Controller\BWController;

class CategoryController extends BWController
{
    
    public function categoryAction($id) {
        $data = $this->getPropertyOverload();
        
        $data->category = $this->getDoctrine()->getRepository('BWBlogBundle:Category')->findOneBy(array(
            'id' => $id,
            'published' => TRUE,
        ));
        
        if ( ! $data->category) {
            throw $this->createNotFoundException("Ошибка 404. Запрашиваемая страница не найдена.\nСкорее всего нужно перегенерировать ссылку страницы.");
        }
        
        $data->categories = $this->getDoctrine()->getRepository('BWBlogBundle:Category')->findBy(
            array(
                'parent' => $data->category->getId(),
                'published' => TRUE,
            )
        );

        $data->posts = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->findNestedBy(
            $data->category->getLeft(),
            $data->category->getRight()
        );
        
        return $this->render('BWBlogBundle:Category:category.html.twig', $data->toArray());
    }
}
