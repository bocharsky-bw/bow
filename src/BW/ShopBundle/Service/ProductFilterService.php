<?php

namespace BW\ShopBundle\Service;

use BW\CustomBundle\Entity\Field;
use BW\CustomBundle\Entity\Property;
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


    /**
     * The construct
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param null|array|ArrayCollection $collection
     * @return Form
     */
    public function createProductFilterForm($collection = null)
    {
        $fields = $this->container->get('doctrine')
            ->getRepository('BWCustomBundle:Field')
            ->findBy(array(
                'used' => true, // only used in filter
            ))
        ;

        /** @TODO Maybe create form in constructor to prevent form duplicate and improve performance? */
        $builder = $this->container->get('form.factory')->createBuilder('form', null, array(
            'csrf_protection' => false,
        ))
            ->setMethod('POST')
            ->setAction($this->container->get('router')->generate('product_filter_redirect'))
        ;

//        $builder
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
//            ->add('properties', 'entity', array(
//                'class' => 'BW\CustomBundle\Entity\Property',
//                'property' => 'name',
//                'expanded' => true,
//                'multiple' => true,
//                'label' => 'Дополнительные поля',
//            ))
//        ;

        /* Add recursively Custom Field Property groups */
        foreach ($fields as $index => $field) {
            /** @var Field $field */
            $builder->add($field->getId(), 'entity', array(
                'class' => 'BWCustomBundle:Property',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) use ($field) {
                    return $er->createQueryBuilder('cfp')
                        ->where('cfp.field = :field_id')
                        ->setParameter('field_id', $field->getId())
                        ->orderBy('cfp.name', 'ASC')
                    ;
                },
                'data' => $collection, // bind data from redirect URL
                'label' => $field->getName(),
                'empty_value' => 'Нет',
                'required' => false,
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

                if (0 < $count) { // append all IDs to ArrayObject
                    if (4 === $fieldId) {
                        /** @var Property $property */
                        foreach ($collection as $property) {
                            $keysVendor->append($property->getId()); // append unique ID to ArrayObject
                        }
                    } elseif (3 === $fieldId) {
                        /** @var Property $property */
                        foreach ($collection as $property) {
                            $keysCategory->append($property->getId()); // append unique ID to ArrayObject
                        }
                    } else {
                        /** @var Property $property */
                        foreach ($collection as $property) {
                            $keysProperty->append($property->getId()); // append unique ID to ArrayObject
                        }
                    }
                }
            }


            // define direct URL
            if (1 === $keysVendor->count() && 0 === $keysCategory->count() && 0 === $keysProperty->count()) {
                $vendor = $this->container->get('doctrine.orm.entity_manager')
                    ->getRepository('BWShopBundle:Vendor')
                    ->findOneBy(array(
                        'property' => $keysVendor->offsetGet(0),
                    ))
                ;

                $url = $this->container->get('router')->generate('vendor_show_by_slug', array(
                    'slug' => $vendor->getSlug(),
                ), true);

                $keysVendor->exchangeArray(array()); // clear vendor IDs array
            } elseif (0 === $keysVendor->count() && 1 === $keysCategory->count() && 0 === $keysProperty->count()) {
                $category = $this->container->get('doctrine.orm.entity_manager')
                    ->getRepository('BWShopBundle:Category')
                    ->findOneBy(array(
                        'property' => $keysCategory->offsetGet(0),
                    ))
                ;

                $url = $this->container->get('request_stack')->getCurrentRequest()->getUriForPath(
                    $category->getRoute()->getPath()
                );

                $keysCategory->exchangeArray(array()); // clear category IDs array
            }

            // generate IDs for filter query string
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

            // create filter query string from IDs
            if ($keys->count()) {
                $url .= '/'; // add slash (/) at begin of filter query string
                $url .= implode('-', (array)$keys); // create filter query string
                $url .= '-*'; // add asterisk sign (*) at the end of filter query string
            }
        }

        return $url;
    }
}
