<?php

namespace BW\BlogBundle\Form;

use Doctrine\ORM\EntityRepository;
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
                'label' => 'Поле ',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('customFieldProperties', 'entity', array(
                'class' => 'BW\BlogBundle\Entity\CustomFieldProperty',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('cfp')
                        ->orderBy('cfp.customField', 'ASC');
                },
                'group_by' => 'customField',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Свойства ',
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'height: 120px;'
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
