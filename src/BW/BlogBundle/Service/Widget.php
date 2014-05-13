<?php

namespace BW\BlogBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Widget {
    
    /**
     * The Service Container
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    
    /**
     * The constructor
     * @global \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @global \Symfony\Component\Form\FormBuilderInterface $form
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    /**
     * Список категорий
     * @param null|int $category
     */
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

    /**
     * Список последних постов категории / нескольких категорий
     * @param int $count Количество постов
     * @param bool|int|array $categories
     */
    public function lastPosts($count = 5, $categories = FALSE) {
        $criteria = array(
            'published' => TRUE,
        );

        if ($categories) {
            if (is_array($categories)) {
                foreach ($categories as &$category) {
                    $category = (int)$category;
                }
                unset($category); // unset reference
                $criteria['category'] = $categories;
            } else {
                $criteria['category'] = (int)$categories;
            }
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

    /**
     * Фильтр для настраиваемых параметров
     */
    public function customFilter() {
        
        $fields = $this->container->get('doctrine')
            ->getRepository('BWBlogBundle:CustomField')
            ->findAll();
        
//        $properteis = $this->container->get('doctrine')
//            ->getRepository('BWBlogBundle:CustomFieldProperty')
//            ->findAll();
        
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