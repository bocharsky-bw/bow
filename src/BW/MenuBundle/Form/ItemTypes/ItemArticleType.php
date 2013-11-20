<?php

namespace BW\MenuBundle\Form\ItemTypes;

use BW\MenuBundle\Form\ItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemArticleType extends ItemType
{
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
                ->add('article', 'entity', array(
                    'class' => 'BWBlogBundle:Article',
                    'property' => 'heading',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->where('a.lang = 1');
                    },
                ))
            ;
    }
    
}
