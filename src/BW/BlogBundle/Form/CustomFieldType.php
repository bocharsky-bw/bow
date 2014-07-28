<?php

namespace BW\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomFieldType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('expanded', 'checkbox', array(
                'required' => false,
                'label' => 'Расширенный вид ',
                'attr' => array(
                    'title' => 'Выпадающий список | Чекбоксы'
                ),
            ))
            ->add('multiple', 'checkbox', array(
                'required' => false,
                'label' => 'Множественный выбор ',
                'attr' => array(
                    'title' => 'Радио-кнопки | Чекбоксы'
                ),
            ))
            ->add('used', 'checkbox', array(
                'required' => false,
                'label' => 'Используется в фильтре ',
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
            'data_class' => 'BW\BlogBundle\Entity\CustomField'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_custom_field';
    }
}
