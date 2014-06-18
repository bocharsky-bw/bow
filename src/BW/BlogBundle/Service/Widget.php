<?php

namespace BW\BlogBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Widget
 * @package BW\BlogBundle\Service
 */
class Widget {

    /**
     * The Service Container
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * The constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }


    /**
     * Список категорий
     *
     * @param null|int|array $categories ID категории
     * @param string $template
     */
    public function listCategories($categories = null, $template = 'list-categories')
    {
        $criteria = array(
            'published' => true,
        );

        if ($categories) {
            if (is_array($categories)) {
                foreach ($categories as &$category) {
                    $category = (int)$category;
                }
                unset($category); // unset reference
                $criteria['parent'] = $categories;
            } else {
                $criteria['parent'] = (int)$categories;
            }
        }

        $categories = $this->container->get('doctrine')
            ->getRepository('BWBlogBundle:Category')
            ->findBy($criteria, array(
                'left' => 'ASC',
            ));

        return $this->container
            ->get('templating')
            ->render("BWBlogBundle:Widget/Category:{$template}.html.twig", array(
                'categories' => $categories,
            ));
    }

    /**
     * Список последних постов категории / нескольких категорий
     *
     * @param int $count Количество постов
     * @param null|int|array $categories ID категории
     * @param string $template Имя шаблона
     *
     */
    public function lastPosts($count = 5, $categories = null, $template = 'last-posts')
    {
        $criteria = array(
            'published' => true,
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
            ->findBy($criteria, array(
                'created' => 'DESC',
            ), $count);

        return $this->container
            ->get('templating')
            ->render("BWBlogBundle:Widget/Post:{$template}.html.twig", array(
                'posts' => $posts,
            ));
    }

    /**
     * The search form
     */
    public function searchFrom()
    {
        $form = $this->container->get('form.factory')->createBuilder()
            ->setMethod('GET')
            ->setAction($this->container->get('router')->generate('search'))
            ->add('query', 'text', array(
                'label' => ' ',
                'attr' => array(
                    'placeholder' => 'Что ищем?..',
                ),
            ))
            ->add('search', 'submit', array(
                'label' => 'Найти'
            ))
            ->getForm()
        ;

        return $this->container->get('templating')
            ->render('BWBlogBundle:Widget:search-form.html.twig', array(
                'form' => $form->createView(),
            ));
    }

    /**
     * Фильтр для настраиваемых параметров
     */
    public function customFilter()
    {
        $fields = $this->container->get('doctrine')
            ->getRepository('BWBlogBundle:CustomField')
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