<?php

namespace BW\ShopBundle\Form;

use BW\ShopBundle\Entity\Product;
use BW\ShopBundle\Entity\ProductCustomField;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProductCustomFieldType
 * @package BW\ShopBundle\Form
 */
class ProductCustomFieldType extends AbstractType
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
        /** @var ProductCustomField $productCustomField */
        $productCustomField = $this->product->getProductCustomFields()->get($index);
        $customField = $productCustomField->getCustomField();

        $productCustomField->setProduct($this->product);

        $builder
//            ->add('customField', 'entity', array(
//                'class' => 'BW\BlogBundle\Entity\CustomField',
//                'property' => 'name',
//                'disabled' => true,
//                // 'label' => 'Поле ',
//                'attr' => array(
//                    'class' => 'form-control',
//                ),
//            ))
            ->add('customFieldProperties', 'entity', array(
                'class' => 'BW\BlogBundle\Entity\CustomFieldProperty',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($customField) {
                    return $er->createQueryBuilder('cfp')
                        ->where('cfp.customField = :customField')
                        ->setParameter('customField', $customField)
                        ->orderBy('cfp.name', 'ASC')
                    ;
                },
                // 'group_by' => 'customField',
                'multiple' => true,
                'expanded' => false,
                'label' => $customField->getName(),
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
            'data_class' => 'BW\ShopBundle\Entity\ProductCustomField'
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
