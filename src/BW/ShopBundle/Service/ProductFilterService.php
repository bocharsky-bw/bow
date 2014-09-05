<?php

namespace BW\ShopBundle\Service;

use BW\BlogBundle\Entity\CustomField;
use BW\BlogBundle\Entity\CustomFieldProperty;
use BW\ShopBundle\Entity\Category;
use BW\ShopBundle\Entity\Vendor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;

/**
 * Class ProductFilterService
 * @package BW\ShopBundle\Service
 */
class ProductFilterService
{
    /**
     * @var ContainerInterface
     */
    private $container;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getProductFilterForm()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $form = $this->createProductFilterForm();
        $form->handleRequest($request);

        return $form;
    }

    /**
     * @return Form
     */
    public function createProductFilterForm()
    {
        $fields = $this->container->get('doctrine')
            ->getRepository('BWBlogBundle:CustomField')
            ->findBy(array(
                'used' => true, // only used in filter
            ))
        ;

        /** @TODO Maybe create form in constructor to prevent form duplicate and improve performance? */
        $builder = $this->container->get('form.factory')->createBuilder('form', null, array(
            'csrf_protection' => false,
        ))
            ->setMethod('POST')
            ->setAction($this->container->get('router')->generate('product_filter_form_handle'))
        ;

        $builder
//            ->add('vendor', 'entity', array(
//                'class' => 'BW\ShopBundle\Entity\Vendor',
//                'property' => 'heading',
//                'expanded' => true,
//                'multiple' => true,
//                'label' => 'Производитель',
//            ))
//            ->add('category', 'entity', array(
//                'class' => 'BW\ShopBundle\Entity\Category',
//                'property' => 'heading',
//                'expanded' => true,
//                'multiple' => true,
//                'label' => 'Категория ',
//            ))
            // Other custom fields
//            // DEMO
//            ->add('color', 'entity', array(
//                'class' => 'BW\BlogBundle\Entity\CustomFieldProperty',
//                'property' => 'name',
//                'expanded' => true,
//                'multiple' => true,
//                'label' => 'Дополнительные поля',
//            ))
        ;

        /* Add recursively Custom Field Property groups */
        foreach ($fields as $index => $field) {
            /** @var CustomField $field */
            $builder->add($field->getId(), 'entity', array(
                'class' => 'BWBlogBundle:CustomFieldProperty',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($field) {
                    return $er->createQueryBuilder('cfp')
                        ->where('cfp.customField = :field_id')
                        ->setParameter('field_id', $field->getId())
                        ->orderBy('cfp.name', 'ASC')
                    ;
                },
                'label' => $field->getName(),
                'empty_value' => 'Нет',
                'required' => FALSE,
                'expanded' => $field->isExpanded(),
                'multiple' => $field->isMultiple(),
            ));
        }

        $builder->add('apply', 'submit', array(
            'label' => 'Применить',
        ));

        return $builder->getForm();
    }

    public function generateUrl()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $form = $this->createProductFilterForm();
        $form->handleRequest($request);

        $url = $this->container->get('router')->generate('product_list', array(), true); // Default redirect URL
        if ($form->isValid()) {
            $formData = $form->getData();

            $keysVendor = new \ArrayObject();
            $keysCategory = new \ArrayObject();
            $keysProperty = new \ArrayObject();
            /** @var Collection $collection */
            foreach ($formData as $fieldId => $collection) {
                $count = $collection->count();

                if (0 < $count) { // append all keys to ArrayObject
                    if (4 === $fieldId) {
                        /** @var CustomFieldProperty $property */
                        foreach ($collection as $property) {
                            $keysVendor->append($property->getId()); // append unique key to ArrayObject
                        }
                    } elseif (3 === $fieldId) {
                        /** @var CustomFieldProperty $property */
                        foreach ($collection as $property) {
                            $keysCategory->append($property->getId()); // append unique key to ArrayObject
                        }
                    } else {
                        /** @var CustomFieldProperty $property */
                        foreach ($collection as $property) {
                            $keysProperty->append($property->getId()); // append unique key to ArrayObject
                        }
                    }
                }
            }


            // define direct URL
            if (1 === $keysVendor->count() && 0 === $keysCategory->count() && 0 === $keysProperty->count()) {
                $vendor = $this->container->get('doctrine.orm.entity_manager')
                    ->getRepository('BWShopBundle:Vendor')
                    ->findOneBy(array(
                        'customFieldProperty' => $keysVendor->offsetGet(0),
                    ))
                ;

                $url = $this->container->get('router')->generate('vendor_show_by_slug', array(
                    'slug' => $vendor->getSlug(),
                ), true);

                $keysVendor->exchangeArray(array()); // clear vendor keys array
            } elseif (0 === $keysVendor->count() && 1 === $keysCategory->count() && 0 === $keysProperty->count()) {
                $category = $this->container->get('doctrine.orm.entity_manager')
                    ->getRepository('BWShopBundle:Category')
                    ->findOneBy(array(
                        'customFieldProperty' => $keysCategory->offsetGet(0),
                    ))
                ;

                $url = $this->container->get('request_stack')->getCurrentRequest()->getUriForPath(
                    $category->getRoute()->getPath()
                );

                $keysCategory->exchangeArray(array()); // clear categories keys array
            }

            // generate SEF keys string
            $keys = new \ArrayObject();
            if ($keysVendor->count()) {
                $keys->append(implode('-', (array)$keysVendor));
            }
            if ($keysCategory->count()) {
                $keys->append(implode('-', (array)$keysCategory));
            }
            if ($keysProperty->count()) {
                $keys->append(implode('-', (array)$keysProperty));
            }
            $url .= implode('-', (array)$keys);

            // mark SEF keys string with asterisk (*) if exists
            if ($keys->count()) {
                $url .= '-*';
            }
        }

        return $url;
    }
}
