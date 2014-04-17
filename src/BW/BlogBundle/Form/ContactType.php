<?php

namespace BW\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heading', 'text')
            // Lang
            ->add('lang', 'entity', array(
                'class' => 'BWLocalizationBundle:Lang',
                'property' => 'name',
                'required' => FALSE,
                'empty_value' => 'Выберите язык',
            ))
            ->add('country', 'text', array(
                'required' => FALSE,
            ))
            ->add('city', 'text', array(
                'required' => FALSE,
            ))
            ->add('street', 'text', array(
                'required' => FALSE,
            ))
            ->add('house', 'text', array(
                'required' => FALSE,
            ))
            ->add('office', 'text', array(
                'required' => FALSE,
            ))
            ->add('person', 'text', array(
                'required' => FALSE,
            ))
            ->add('email', 'text', array(
                'required' => FALSE,
            ))
            ->add('phone', 'text', array(
                'required' => FALSE,
            ))
            ->add('skype', 'text', array(
                'required' => FALSE,
            ))
            ->add('companyName', 'text', array(
                'required' => FALSE,
            ))
            ->add('companyDescription', 'text', array(
                'required' => FALSE,
            ))
            ->add('map', 'textarea', array(
                'required' => FALSE,
            ))
            ->add('slug', 'text', array(
                'required' => FALSE,
            ))
            ->add('title', 'text', array(
                'required' => FALSE,
            ))
            ->add('metaDescription', 'textarea', array(
                'required' => FALSE,
            ))
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
            'data_class' => 'BW\BlogBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_blogbundle_contact';
    }
}
