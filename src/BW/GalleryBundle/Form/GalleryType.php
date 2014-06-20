<?php

namespace BW\GalleryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GalleryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Название ',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('shortDescription', 'textarea', array(
                'label' => 'Короткое описание ',
                'required' => false,
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
            'data_class' => 'BW\GalleryBundle\Entity\Gallery'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_gallery';
    }
}
