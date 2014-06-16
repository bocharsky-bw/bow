<?php

namespace BW\RouterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BW\MainBundle\Controller\BWController;

class RouterController extends BWController
{
    /**
     * Route instance
     * @var \BW\RouterBundle\Entity\Route
     */
    public $route;
    
    public function indexAction($q) {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        
        // Получаем entity текущего языка
        $lang = $this->get('bw_localization.lang');
        
        // Поиск роута с учетом текущего языка
        $this->route = $em->getRepository('BWRouterBundle:Route')->findRouteBy($q, $request->getLocale());
        if ( ! $this->route) {
            // Поиск роута без учета текущего языка
            $this->route = $em->getRepository('BWRouterBundle:Route')->findRouteBy($q);
            if ( ! $this->route) {
                throw $this->createNotFoundException("Ошибка 404. Запрашиваемая Вами страница не найдена");
            }
        }
        
        $defaults = $this->route->getDefaults();
        return $this->forward($this->route->getController(), $defaults);
        
        /* 
        // for_testing
        if ($q == 'blog') {
            return $this->forward('BWBlogBundle:Article:articles');
        } elseif ($q == 'article') {
            return $this->forward('BWBlogBundle:Article:article',
                    array(
                        'slug' => 'article1',
                    )
                );
        }
        */
        
        //return $this->render('BWRouterBundle:Router:index.html.twig');
    }
}
