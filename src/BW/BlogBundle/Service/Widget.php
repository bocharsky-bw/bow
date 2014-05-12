<?php

namespace BW\BlogBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Widget {
    
    /**
     * The Service Container
     * @var Symfony\Component\DependencyInjection\ContainerInterface 
     */
    protected $container;

    
    /**
     * The constructor
     * @global Symfony\Component\Form\FormFactoryInterface $formFactory
     * @global Symfony\Component\Form\FormBuilderInterface $form
     */
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
        
        return $this->container->get('templating')
            ->render('BWBlogBundle:Widget:list-categories.html.twig', array(
                'categories' => $categories,
            ));
    }
    
    public function lastPosts($count = 5, $category = FALSE) {
        $criteria = array(
            'published' => TRUE,
        );
        
        if ($category) {
            $criteria['category'] = $category;
        }
        
        $posts = $this->container->get('doctrine')
            ->getRepository('BWBlogBundle:Post')
            ->findBy(
                $criteria,
                array(
                    'created' => 'DESC',
                ),
                $count
            );
        
        return $this->container->get('templating')
            ->render('BWBlogBundle:Widget:last-posts.html.twig', array(
                'posts' => $posts,
            ));
    }
    
    public function customFilter() {
        
        $fields = $this->container->get('doctrine')
            ->getRepository('BWBlogBundle:CustomField')
            ->findAll();
        
        $properteis = $this->container->get('doctrine')
            ->getRepository('BWBlogBundle:CustomFieldProperty')
            ->findAll();
        
        $defaults = array('properteis' => new \Doctrine\Common\Collections\ArrayCollection);
        $formFactory = $this->container->get('form.factory');
        $form = $formFactory->createBuilder('form', $defaults, array('csrf_protection' => false))
            ->setMethod('GET')
            ->add('properteis', 'entity', array(
                'class' => 'BWBlogBundle:CustomFieldProperty',
                'property' => 'name',
                'group_by' => 'customField',
                'required' => FALSE,
                'expanded' => TRUE,
                'multiple' => TRUE,
            ))
            ->add('apply', 'submit')
            ->getForm();
        
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $form->handleRequest($request);
        
        return $this->container->get('templating')
            ->render('BWBlogBundle:Widget:custom-filter.html.twig', array(
                'form' => $form->createView(),
                'fields' => $fields,
            ));
    }
    
}