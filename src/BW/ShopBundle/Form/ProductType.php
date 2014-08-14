<?php

namespace BW\ShopBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \BW\ShopBundle\Entity\Product $entity */
        $entity = $options['data'];

        $builder
            ->add('published', 'checkbox', array(
                'required' => false,
                'label' => 'Опубликовано',
            ))
            ->add('recent', 'checkbox', array(
                'required' => false,
                'label' => 'Новый',
            ))
            ->add('featured', 'checkbox', array(
                'required' => false,
                'label' => 'Рекомендуемый',
            ))
            ->add('popular', 'checkbox', array(
                'required' => false,
                'label' => 'Популярный',
            ))
            ->add('category', 'entity', array(
                'class' => 'BWShopBundle:Category',
                //'property' => 'heading',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.left', 'ASC')
                    ;
                },
                'required' => false,
                'label' => 'Категория',
                'empty_value' => '< Без категории >',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('vendor', 'entity', array(
                'class' => 'BW\ShopBundle\Entity\Vendor',
                'property' => 'heading',
                'required' => true,
                'empty_value' => '< Без производителя >',
                'label' => 'Производитель',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('sku', 'text', array(
                'required' => true,
                'label' => 'Артикул',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('price', 'text', array(
                'required' => true,
                'label' => 'Цена',
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
            ->add('created', 'datetime', array(
                'required' => false,
                'label' => 'Создано',
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
            ->add('productImages', 'collection', array(
                'type' => new ProductImageType($entity),
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\ShopBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_product';
    }
}
