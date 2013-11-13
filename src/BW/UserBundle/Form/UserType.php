<?php

namespace BW\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('isActive', 'checkbox', array(
                    'required' => FALSE,
                ))
                ->add('username', 'text')
                ->add('email', 'email')
                ->add('roles', 'collection', array(
                    // each item in the array will be an "email" field
                    'type' => 'entity',
                    'allow_add' => TRUE,
                    'allow_delete' => TRUE,
                    // these options are passed to each "email" type
                    'options'  => array(
                        'required'  => false,
                        'class' => 'BWUserBundle:Role',
                        'property' => 'name',
                    ),
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
            'data_class' => 'BW\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}
