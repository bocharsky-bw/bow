<?php

namespace BW\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostCustomFieldType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customField', 'entity', array(
                'class' => 'BW\BlogBundle\Entity\CustomField',
                'property' => 'name',
                'disabled' => true,
            ))
            ->add('customFieldProperties', 'entity', array(
                'class' => 'BW\BlogBundle\Entity\CustomFieldProperty',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\BlogBundle\Entity\PostCustomField'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_post_custom_field';
    }
}
