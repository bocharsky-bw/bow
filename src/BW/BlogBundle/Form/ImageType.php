<?php

namespace BW\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{

    /**
     * @var string The uploads subfolder
     */
    private $subFolder;

    /**
     * @param string $subFolder The uploads subfolder
     */
    public function __construct($subFolder = '')
    {
        $this->subFolder = (string)$subFolder;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => false,
                'trim' => true,
            ))
            ->add('alt', 'text', array(
                'required' => false,
            ))
            ->add('subFolder', 'hidden', array(
                'data' => $this->subFolder,
                'required' => false,
            ))
            ->add('file')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\BlogBundle\Entity\Image'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'image';
    }
}
