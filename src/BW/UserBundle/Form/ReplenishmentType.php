<?php

namespace BW\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReplenishmentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('additiveAmount', 'number')
            ->add('currency', 'entity', array(
                'class' => 'BW\UserBundle\Entity\Currency',
                'property' => 'name',
            ))
            ->add('replenish', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\UserBundle\Entity\Replenishment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_userbundle_replenishment';
    }
}
