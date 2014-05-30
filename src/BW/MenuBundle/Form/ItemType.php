<?php

namespace BW\MenuBundle\Form;

use BW\BlogBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('href', 'text', array(
                    'required' => FALSE,
                ))
                ->add('name', 'text')
                ->add('title', 'text', array(
                    'required' => FALSE,
                ))
                ->add('class', 'text', array(
                    'required' => FALSE,
                ))
                ->add('blank', 'checkbox', array(
                    'required' => FALSE,
                ))
                ->add('ordering', 'number')
                // Entities
                // Lang
                ->add('menu', 'entity', array(
                    'class' => 'BWMenuBundle:Menu',
                    'property' => 'name',
                ))
                ->add('parent', 'entity', array(
                    'class' => 'BWMenuBundle:Item',
                    //'property' => 'name',
                    'group_by' => 'menu.name',
                    'required' => FALSE,
                    'empty_value' => 'Корневой пункт меню',
                ))
//                ->add('route', 'entity', array(
//                    'class' => 'BWRouterBundle:Route',
//                    'query_builder' => function(EntityRepository $er) {
//                        return $er->createQueryBuilder('r')
//                                ->orderBy('r.path', 'ASC');
//                    },
//                    'property' => 'path',
//                    'required' => FALSE,
//                    'empty_value' => 'Ручная ссылка',
//                ))
                ->add('lang', 'entity', array(
                    'class' => 'BWLocalizationBundle:Lang',
                    'property' => 'name',
                    'required' => FALSE,
                    'empty_value' => 'Выберите язык',
                ))
                ->add('image', new ImageType('posts'), array(
                    'required' => FALSE,
                ))
                // Buttons
                ->add('save', 'submit')
                ->add('saveAndClose', 'submit')
                ->add('delete', 'submit')
            ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\MenuBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'item';
    }
}
