<?php

namespace BW\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Entity\Article;
use BW\BlogBundle\Form\ArticleType;

class ArticleController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Article $article
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function articlesAction() {
        $data = $this->getPropertyOverload();
        
        $data->articles = $this->getDoctrine()->getRepository('BWBlogBundle:Article')->findAll();
        
        return $this->render('BWBlogBundle:Admin/Article:articles.html.twig', $data->toArray());
    }
    
    public function articleAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $article = $this->getDoctrine()->getRepository('BWBlogBundle:Article')->find($id);
        } else {
            $article = new Article;
        }
        
        $form = $this->createForm(new ArticleType(), $article);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($article);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Статья успешно удалена из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_articles') );
                    }
                }
                
                $em->persist($article);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Статья успешно сохранена в БД'
                    );
                
                if ( $form->get('saveAndExit')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_articles') );
                }
                
                return $this->redirect( $this->generateUrl('admin_article_edit', array('id' => $article->getId())) );
            }
        }
        
        $data->article = $article;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/Article:edit-article.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/Article:add-article.html.twig', $data->toArray());
    }
    
    public function deleteAction() {
        
    }
    
    public function togglePublishedAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $article = $em->getRepository('BWBlogBundle:Article')->find($id);
        $article->setPublished( ! $article->getPublished() );
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('admin_articles'));
    }
}
