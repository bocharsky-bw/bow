<?php

namespace BW\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CurrencyType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('abbr', 'text')
            ->add('symbol', 'text')
            ->add('alpha3', 'text')
            ->add('numericCode', 'number')
            ->add('exchangeRate', 'number')
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
            'data_class' => 'BW\UserBundle\Entity\Currency'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_userbundle_currency';
    }
}
