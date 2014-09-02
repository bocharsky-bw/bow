<?php

namespace BW\ShopBundle\Service;
use BW\BlogBundle\Entity\CustomFieldProperty;
use BW\ShopBundle\Entity\Category;
use BW\ShopBundle\Entity\Vendor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var array
     */
    private $formDataContainer;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @return Form
     */
    public function createProductFilterForm()
    {
        /** @TODO Maybe create form in constructor to prevent form duplicate and improve performance? */
        $builder = $this->container->get('form.factory')->createBuilder('form', null, array(
            'csrf_protection' => false,
        ))->setMethod('GET');

        $builder
            ->add('vendor', 'entity', array(
                'class' => 'BW\ShopBundle\Entity\Vendor',
                'property' => 'heading',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Производитель',
            ))
            ->add('category', 'entity', array(
                'class' => 'BW\ShopBundle\Entity\Category',
                'property' => 'heading',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Категория ',
            ))
            // Other custom fields
            // DEMO
            ->add('color', 'entity', array(
                'class' => 'BW\BlogBundle\Entity\CustomFieldProperty',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Дополнительные поля',
            ))
            ->add('apply', 'submit', array(
                'label' => 'Применить',
            ))
        ;

        return $builder->getForm();
    }

    public function generateUrl(Form $form)
    {
        $formData = $form->getData();

        $keysVendor = new \ArrayObject();
        $keysCategory = new \ArrayObject();
        $keysProperty = new \ArrayObject();
        /** @var Collection $collection */
        foreach ($formData as $alias => $collection) {
            $count = $collection->count();
            if ('vendor' === $alias) {
                if (0 < $count) { // append all keys to ArrayObject
                    if (1 === $count) { // generate direct URL
                        $urlVendor = $this->container->get('router')->generate('vendor_show_by_slug', array(
                            'slug' => $collection->first()->getSlug(),
                        ), true);
                    }

                    /** @var Vendor $vendor */
                    foreach ($collection as $vendor) {
                        $keysVendor->append($vendor->getSlug()); // append unique key to ArrayObject
                    }
                }
            } elseif ('category' === $alias) {
                if (0 < $count) { // append all keys to ArrayObject
                    if (1 === $count) { // generate direct URL
                        $urlCategory = $this->container->get('request_stack')->getCurrentRequest()->getUriForPath(
                            $collection->first()->getRoute()->getPath()
                        );
                    }
                    /** @var Category $category */
                    foreach ($collection as $category) {
                        $keysCategory->append($category->getId()); // append unique key to ArrayObject
                    }
                }
            } else {
                if (0 < $count) { // append all keys to ArrayObject
                    /** @var CustomFieldProperty $category */
                    foreach ($collection as $property) {
                        $keysProperty->append($property->getId()); // append unique key to ArrayObject
                    }
                }
            }
        }

        // define direct url
        if (isset($urlVendor) && 0 === $keysCategory->count() && 0 === $keysProperty->count()) {
            $url = $urlVendor . '/';
            $keysVendor->exchangeArray(array()); // clear vendor keys array
        } elseif (isset($urlCategory)) {
            $url = $urlCategory . '/';
            $keysCategory->exchangeArray(array()); // clear categories keys array
        } else {
            $url = $this->container->get('router')->generate('product_list', array(), true);
        }

        // generate SEF keys string
        $keys = new \ArrayObject();
        if ($keysVendor->count()) {
            $keys->append(implode('-', (array)$keysVendor));
        }
        if ($keysProperty->count()) {
            $keys->append(implode('-', (array)$keysProperty));
        }
        $url .= implode('-', (array)$keys);

        // mark SEF keys string with asterisk (*) if exists
        if ($keys->count()) {
            $url .= '-*';
        }

        // generate query string
        if ($keysCategory->count()) {
            $url .= '?category=' . implode('-', (array)$keysCategory);
        }

        return $url;
    }
} 