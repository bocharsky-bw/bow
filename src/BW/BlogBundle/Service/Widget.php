<?php

namespace BW\BlogBundle\Service;

use BW\BlogBundle\Entity\CustomField;
use Doctrine\ORM\EntityRepository;
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
    public function searchForm()
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
            ->findBy(array(
                'used' => true, // only used in filter
            ));

        $formFactory = $this->container->get('form.factory');
        $fb = $formFactory->createBuilder('form', null, array(
            'csrf_protection' => false
        ))
            ->setMethod('GET')
        ;

        /* Add recursively Custom Field Property groups */
        foreach ($fields as $index => $field) {
            /** @var CustomField $field */
            $fb->add("field_{$index}", 'entity', array(
                'class' => 'BWBlogBundle:CustomFieldProperty',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($field) {
                    return $er->createQueryBuilder('cfp')
                        ->where('cfp.customField = :field_id')
                        ->setParameter('field_id', $field->getId())
                        ->orderBy('cfp.name', 'ASC')
                    ;
                },
                'label' => $field->getName(),
                'empty_value' => 'Нет',
                'required' => FALSE,
                'expanded' => $field->isExpanded(),
                'multiple' => $field->isMultiple(),
            ));
        }

        /* Add buttons */
        $fb
            ->add('apply', 'submit', array(
                'label' => 'Применить',
            ))
            ->add('reset', 'reset', array(
                'label' => 'Вернуть',
            ))
            ->add('clear', 'button', array(
                'label' => 'Очистить',
            ))
        ;
        $form = $fb->getForm();

        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $form->handleRequest($request);

        return $this->container->get('templating')
            ->render('BWBlogBundle:Widget:custom-filter.html.twig', array(
                'form' => $form->createView(),
                'fields' => $fields,
            ));
    }
}