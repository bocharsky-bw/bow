<?php

namespace BW\MainBundle\Controller;

use BW\MainBundle\Controller\BWController;

class MainController extends BWController
{

    /**
     * Конструктор MainController
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     */
    public function __construct() {
        
        parent::__construct();
        
    }
    
    public function indexAction() {
        $data = $this->getPropertyOverload();
        
        $data->post = $this->getDoctrine()
                ->getRepository('BWBlogBundle:Post')
                ->findOneBy(array(
                    'home' => TRUE,
                    'published' => TRUE,
                    'lang' => $this->get('bw_localization.lang')->getId(),
                ))
            ;
        
        return $this->render('BWMainBundle:Main:index.html.twig', $data->toArray());
    }
    
}
