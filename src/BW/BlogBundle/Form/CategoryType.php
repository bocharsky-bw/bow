<?php

namespace BW\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

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
                    'required' => FALSE,
                ))
                ->add('heading', 'text')
                // Entities
                // Lang
                ->add('parent', 'entity', array(
                    'class' => 'BWBlogBundle:Category',
                    //'property' => 'heading',
                    'query_builder' => function(EntityRepository $er) use($options) {
                        return $er->createQueryBuilder('c')
                                ->where('c.id != :id')
                                ->setParameter('id', $options['data']->getId())
                                ->andWhere('c.left < :left OR c.left > :right')
                                ->setParameter('left', $options['data']->getLeft())
                                ->setParameter('right', $options['data']->getRight())
                                ->orderBy('c.left', 'ASC')
                            ;
                    },
                    'required' => FALSE,
                    'empty_value' => 'Корневая',
                ))
                ->add('lang', 'entity', array(
                    'class' => 'BWLocalizationBundle:Lang',
                    'property' => 'name',
                    'required' => FALSE,
                    'empty_value' => 'Выберите язык',
                ))
                // Meta tags
                ->add('slug', 'text', array(
                    'required' => FALSE,
                ))
                ->add('title', 'text', array(
                    'required' => FALSE,
                ))
                ->add('metaDescription', 'textarea', array(
                    'required' => FALSE,
                ))
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
            'data_class' => 'BW\BlogBundle\Entity\Category'
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
