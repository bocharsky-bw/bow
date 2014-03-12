<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;

class PostController extends BWController
{
    

    public function postAction($id) {
        $data = $this->getPropertyOverload();
        
        $lang = $this->get('bw_localization.lang')->getCurrentLangEntity();
        
        $data->post = $this->getDoctrine()->getRepository('BWBlogBundle:Post')->findOneBy(
            array(
                'id' => $id,
                'published' => TRUE,
                //'lang' => $lang,
            )
        );
        
        if ( ! $data->post) {
            throw $this->createNotFoundException("Ошибка 404. Запрашиваемая статья не найдена.\nСкорее всего нужно перегенерировать ссылку страницы.");
        }
        
        return $this->render('BWBlogBundle:Post:post.html.twig', $data->toArray());
    }
}
