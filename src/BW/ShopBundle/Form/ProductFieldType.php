<?php

namespace BW\ShopBundle\Form;

use BW\ShopBundle\Entity\Product;
use BW\ShopBundle\Entity\ProductField;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProductFieldType
 * @package BW\ShopBundle\Form
 */
class ProductFieldType extends AbstractType
{
    /**
     * @var \BW\ShopBundle\Entity\Product
     */
    private $product;


    /**
     * The constructor
     *
     * @param \BW\ShopBundle\Entity\Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $index = (int)str_replace(array('[', ']'), '', $options['property_path']);
        /** @var ProductField $productField */
        $productField = $this->product->getProductFields()->get($index);
        $field = $productField->getField();

        $productField->setProduct($this->product);

        $builder
//            ->add('field', 'entity', array(
//                'class' => 'BW\CustomBundle\Entity\Field',
//                'property' => 'name',
//                'disabled' => true,
//                // 'label' => 'Поле ',
//                'attr' => array(
//                    'class' => 'form-control',
//                ),
//            ))
            ->add('properties', 'entity', array(
                'class' => 'BW\CustomBundle\Entity\Property',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($field) {
                    return $er->createQueryBuilder('cfp')
                        ->where('cfp.field = :field')
                        ->setParameter('field', $field)
                        ->orderBy('cfp.name', 'ASC')
                    ;
                },
                // 'group_by' => 'field',
                'multiple' => true,
                'expanded' => false,
                'label' => $field->getName(),
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
            'data_class' => 'BW\ShopBundle\Entity\ProductField'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_product_custom_field';
    }
}
