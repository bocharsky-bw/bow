<?php

namespace BW\MenuBundle\Form\ItemTypes;

use BW\MenuBundle\Form\ItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemArticlesType extends ItemType
{
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
//        $builder
//                ->add('post', 'entity', array(
//                    'class' => 'BWBlogBundle:Post',
//                    'property' => 'heading',
//                ))
//            ;
    }
    
}
