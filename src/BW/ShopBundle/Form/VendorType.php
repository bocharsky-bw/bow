<?php

namespace BW\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VendorType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('heading', 'text', array(
                'required' => true,
                'label' => 'Заголовок',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('description', 'text', array(
                'required' => false,
                'label' => 'Описание',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\ShopBundle\Entity\Vendor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_shopbundle_vendor';
    }
}
