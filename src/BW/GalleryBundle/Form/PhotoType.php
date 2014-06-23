<?php

namespace BW\GalleryBundle\Form;

use BW\BlogBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gallery', 'entity', array(
                'class' => 'BW\GalleryBundle\Entity\Gallery',
                'property' => 'name',
                'label' => 'Галерея ',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('name', 'text', array(
                'label' => 'Название ',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('shortDescription', 'textarea', array(
                'label' => 'Короткое описание ',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('smallImage', new ImageType('galleries/small-photo'), array(
                'label' => 'Маленькое изображение ',
                'required' => false,
            ))
            ->add('bigImage', new ImageType('galleries/big-photo'), array(
                'label' => 'Большое изображение ',
                'required' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\GalleryBundle\Entity\Photo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_photo';
    }
}
