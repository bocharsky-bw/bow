<?php

namespace BW\BlogBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Widget {
    
    /**
     * The Service Container
     * @var Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;

    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    
    public function listCategories($category = NULL) {
        $criteria = array(
            'published' => TRUE,
        );
        
        if ($category) {
            $category = $categories = $this->container->get('doctrine')
                    ->getRepository('BWBlogBundle:Category')
                    ->find($category)
                ;
            if ($category) {
                $criteria['parent'] = $category->getId();
            }
        }
        
        $categories = $this->container->get('doctrine')
                ->getRepository('BWBlogBundle:Category')
                ->findBy(
                    $criteria,
                    array(
                        'left' => 'ASC',
                    )
                )
            ;
        
        return $this->container->get('templating')->render('BWBlogBundle:Widget:list-categories.html.twig', array(
            'categories' => $categories,
        ));
    }
    
    public function lastPosts($count = 5) {
        $posts = $this->container->get('doctrine')
                ->getRepository('BWBlogBundle:Post')
                ->findBy(
                    array(
                        'published' => TRUE,
                    ),
                    array(
                        'created' => 'DESC',
                    ),
                    $count
                )
            ;
        
        return $this->container->get('templating')->render('BWBlogBundle:Widget:last-posts.html.twig', array(
            'posts' => $posts,
        ));
    }
}