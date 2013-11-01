<?php

namespace BW\MenuBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('menu', 'entity', array(
                    'class' => 'BWMenuBundle:Menu',
                    'property' => 'name',
                ))
                ->add('parent', 'entity', array(
                    'class' => 'BWMenuBundle:Item',
                    'property' => 'name',
                    'group_by' => 'menu.name',
                    'required' => FALSE,
                    'empty_value' => 'Корневой пункт меню',
                ))
                ->add('name', 'text')
                ->add('title', 'text')
                ->add('href', 'text')
                ->add('class', 'text')
                ->add('inNew', 'checkbox', array(
                    'required' => FALSE,
                ))
                ->add('ordering', 'number')
                // Buttons
                ->add('save', 'submit')
                ->add('saveAndExit', 'submit')
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
