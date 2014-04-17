<?php

namespace BW\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', 'checkbox', array(
                'required' => FALSE,
            ))
            ->add('name', 'text')
            ->add('nameEn', 'text')
            ->add('alpha2', 'text')
            ->add('alpha3', 'text')
            ->add('numericCode', 'number')
            ->add('code', 'text')
            ->add('save', 'submit')
            ->add('saveAndClose', 'submit')
            ->add('delete', 'submit')
            ->add('currency', 'entity', array(
                'class' => 'BW\UserBundle\Entity\Currency',
                'property' => 'name',
                'required' => FALSE,
                'empty_value' => 'Не выбрано',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\UserBundle\Entity\Country'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_userbundle_country';
    }
}
