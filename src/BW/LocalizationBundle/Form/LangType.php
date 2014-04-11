<?php

namespace BW\LocalizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LangType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('sign')
                ->add('locale')
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
            'data_class' => 'BW\LocalizationBundle\Entity\Lang'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lang';
    }
}
