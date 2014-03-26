<?php

namespace BW\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileAddressType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', 'entity', array(
                'class' => 'BWUserBundle:Country',
                'property' => 'name',
                'empty_value' => 'Не выбрано',
                'required' => FALSE,
            ))
            ->add('region', 'text', array(
                'required' => FALSE,
            ))
            ->add('city', 'text', array(
                'required' => FALSE,
            ))
            ->add('postcode', 'text', array(
                'required' => FALSE,
            ))
            ->add('street', 'text', array(
                'required' => FALSE,
            ))
            ->add('house', 'text', array(
                'required' => FALSE,
            ))
            ->add('apartment', 'text', array(
                'required' => FALSE,
            ))
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\UserBundle\Entity\Profile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'profile_address';
    }
}
