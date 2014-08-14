<?php

namespace BW\ShopBundle\Form;

use BW\FileBundle\Form\ImageType;
use BW\ShopBundle\Entity\ProductImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductImageType extends AbstractType
{
    private $product;


    public function __construct($product)
    {
        $this->product = $product;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setEmptyData((new ProductImage())->setProduct($this->product));

        $builder
            ->add('image', new ImageType('products'))
//            ->add('product', 'entity', array(
//                'class' => 'BW\ShopBundle\Entity\Product',
//                'property' => 'id',
//                'data' => $this->product,
//            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BW\ShopBundle\Entity\ProductImage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bw_product_image';
    }
}
