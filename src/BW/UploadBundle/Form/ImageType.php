<?php

namespace BW\UploadBundle\Form;

use BW\UploadBundle\Entity\Image;
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
        // May be overridden in parent form type
        $builder->setEmptyData((new Image())->setSubFolder($this->subFolder));

        $builder
            ->add('file', 'file', array(
                'required' => false,
                'label' => 'Выберите изображение',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            // May be overridden in parent form type
//            ->add('subFolder', 'hidden', array(
//                'required' => false,
//                'data' => $this->subFolder,
//                'attr' => array(
//                    'class' => 'form-control',
//                ),
//            ))
            ->add('title', 'text', array(
                'required' => false,
                'trim' => true,
                'label' => 'Всплывающая подсказка (title)',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('alt', 'text', array(
                'required' => false,
                'trim' => true,
                'label' => 'Альтернативный текст (alt)',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('upload', 'submit', array(
                'label' => 'Загрузить',
                'attr' => array(
                    'class' => 'btn btn-primary icon-upload-alt before-padding',
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
            'data_class' => 'BW\UploadBundle\Entity\Image'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_image';
    }
}
