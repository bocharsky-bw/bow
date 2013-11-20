<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;

class ArticleController extends BWController
{
    
    public function articlesAction() {
        $data = $this->getPropertyOverload();
        
        $lang = $this->get('bw.localization.lang')->findLangByLocale();
        
        $data->articles = $this->getDoctrine()->getRepository('BWBlogBundle:Article')->findBy(
            array(
                'published' => TRUE,
                'lang' => $lang,
            ),
            array(
                'created' => 'desc',
            )
        );
        
        return $this->render('BWBlogBundle:Article:articles.html.twig', $data->toArray());
    }
    
    public function articleAction($slug) {
        $data = $this->getPropertyOverload();
        
        $lang = $this->get('bw.localization.lang')->findLangByLocale();
        
        $data->article = $this->getDoctrine()->getRepository('BWBlogBundle:Article')->findOneBy(
            array(
                'slug' => $slug,
                'published' => TRUE,
                'lang' => $lang,
            )
        );
        
        if ( ! $data->article) {
            throw $this->createNotFoundException('Статья по адресу "'. $slug .'" не найдена.');
        }
        
        return $this->render('BWBlogBundle:Article:article.html.twig', $data->toArray());
    }
}
