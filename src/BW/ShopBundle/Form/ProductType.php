<?php

namespace BW\ShopBundle\Form;

use BW\ShopBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
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
        /** @var Product $entity */
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
                'required' => false,
                'empty_value' => '< Без производителя >',
                'label' => 'Производитель',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('sku', 'text', array(
                'required' => false,
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
            // CustomFields
            ->add('customField', 'entity', array(
                'class' => 'BW\BlogBundle\Entity\CustomField',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($entity) {
                    $qb = $er->createQueryBuilder('cf');
                    if ($entity->getId()) {
                        $qb
                            ->leftJoin(
                                'cf.productCustomFields',
                                'pcf',
                                Join::WITH,
                                'cf.id = pcf.customField AND pcf.product = :product'
                            )
                            ->where($qb->expr()->isNull('pcf.product')) // get only unrelated entities
                            ->setParameter('product', $entity)
                        ;
                    }
                    $qb->orderBy('cf.name');

                    return $qb;
                },
                'required' => false,
                'mapped' => false,
                'label' => 'Выберите поле для добавления ',
                'empty_value' => '< Не выбрано >',
                'attr' => array(
                    'class' => 'form-control',
                ),
            ))
            ->add('addCustomField', 'submit', array(
                'label' => 'Добавить',
                'attr' => array(
                    'class' => 'btn btn-primary icon-plus before-padding',
                ),
            ))
        ;

        if ($entity->getProductCustomFields()->count()) {
            $builder->add('productCustomFields', 'collection', array(
                'type' => new ProductCustomFieldType($entity),
                'options' => array(
                    'required' => false,
                ),
                'label' => 'Добавленые поля ',
                'allow_add' => true,
                'allow_delete' => true,
            ));
        }
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
