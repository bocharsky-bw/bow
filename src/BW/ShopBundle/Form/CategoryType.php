<?php

namespace BW\ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('published', 'checkbox', array(
                'required' => false,
                'label' => 'Опубликовано',
            ))
            ->add('parent', 'entity', array(
                'class' => 'BWShopBundle:Category',
                //'property' => 'heading',
                'query_builder' => function(EntityRepository $er) use($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id != :id')
                        ->andWhere('c.left < :left OR c.left > :right')
                        ->setParameter('id', (int)$options['data']->getId())
                        ->setParameter('left', (int)$options['data']->getLeft())
                        ->setParameter('right', (int)$options['data']->getRight())
                        ->orderBy('c.left', 'ASC')
                        ;
                },
                'label' => 'Родительская категория',
                'required' => false,
                'empty_value' => '< Корневая категория >',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('heading', 'text', array(
                'required' => true,
                'label' => 'Заголовок',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('shortDescription', 'textarea', array(
                'required' => false,
                'label' => 'Короткое описание',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('description', 'textarea', array(
                'required' => false,
                'label' => 'Описание',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('order', 'number', array(
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('slug', 'text', array(
                'required' => false,
                'label' => 'Псевдоним URL',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('title', 'text', array(
                'required' => false,
                'label' => 'Заголовок страницы в браузере',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('metaDescription', 'textarea', array(
                'required' => false,
                'label' => 'Описание страницы',
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
            'data_class' => 'BW\ShopBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_category';
    }
}
