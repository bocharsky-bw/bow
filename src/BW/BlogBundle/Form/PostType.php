<?php

namespace BW\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('published', 'checkbox', array(
                'required' => FALSE,
            ))
            ->add('home', 'checkbox', array(
                'required' => FALSE,
            ))
            ->add('heading', 'text')
            ->add('shortDescription', 'textarea', array(
                'required' => FALSE,
            ))
            ->add('content', 'textarea', array(
                'required' => FALSE,
            ))
            ->add('created', 'datetime')
            // Entities
            // Category
            ->add('category', 'entity', array(
                'class' => 'BWBlogBundle:Category',
                //'property' => 'heading',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                            ->orderBy('c.left', 'ASC')
                        ;
                },
                'required' => FALSE,
                'empty_value' => 'Без категории',
            ))
            // Lang
            ->add('lang', 'entity', array(
                'class' => 'BWLocalizationBundle:Lang',
                'property' => 'name',
                'required' => FALSE,
                'empty_value' => 'Выберите язык',
            ))
            ->add('image', new ImageType('categories'), array(
                'required' => false,
            ))
            // Meta tags
            ->add('slug', 'text', array(
                'required' => FALSE,
            ))
            ->add('title', 'text', array(
                'required' => FALSE,
            ))
            ->add('metaDescription', 'textarea', array(
                'required' => FALSE,
            ))
            // Buttons
            ->add('save', 'submit')
            ->add('saveAndClose', 'submit')
            ->add('delete', 'submit')
            // CustomFields
            ->add('custom_field', 'entity', array(
                'class' => 'BW\BlogBundle\Entity\CustomField',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('cf')
                        ->leftJoin('cf.postCustomFields', 'pcf')
                        ->where('pcf.id IS NOT NULL')
                        ->orderBy('cf.name', 'ASC')
                    ;
                },
                'required' => false,
                'mapped' => false,
                'label' => 'Настраиваемые поля ',
            ))
            ->add('add_custom_field', 'submit', array(
                'label' => 'Добавить',
            ))
            ->add('postCustomFields',  'collection', array(
                'type'=> new PostCustomFieldType(),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'BW\BlogBundle\Entity\Post',
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'post';
    }
}
