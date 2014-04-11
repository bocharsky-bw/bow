<?php

namespace BW\SliderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('alias', 'text', array(
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
            'data_class' => 'BW\SliderBundle\Entity\Slider'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_slider';
    }
}
