<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;

class PageController extends BWController
{
    

    public function pageAction($slug) {
        $data = $this->getPropertyOverload();
        
        $lang = $this->get('bw.localization.lang')->findLangByLocale();
        
        $data->page = $this->getDoctrine()->getRepository('BWBlogBundle:Page')->findOneBy(
            array(
                'slug' => $slug,
                'published' => TRUE,
                'lang' => $lang,
            )
        );
        
        if ( ! $data->page) {
            throw $this->createNotFoundException('Статья по адресу "'. $slug .'" не найдена.');
        }
        
        return $this->render('BWBlogBundle:Page:page.html.twig', $data->toArray());
    }
}
