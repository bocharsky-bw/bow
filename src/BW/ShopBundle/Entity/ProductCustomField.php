<?php

namespace BW\ShopBundle\Entity;

use BW\BlogBundle\Entity\CustomField;
use BW\BlogBundle\Entity\CustomFieldProperty;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ProductCustomField
 * @package BW\ShopBundle\Entity
 */
class ProductCustomField
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var CustomField
     */
    private $customField;

    /**
     * @var ArrayCollection
     */
    private $customFieldProperties;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->customFieldProperties = new ArrayCollection();
    }


    /* GETTERS / SETTERS */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param Product $product
     * @return ProductCustomField
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set customField
     *
     * @param \BW\BlogBundle\Entity\CustomField $customField
     * @return ProductCustomField
     */
    public function setCustomField(CustomField $customField = null)
    {
        $this->customField = $customField;

        return $this;
    }

    /**
     * Get customField
     *
     * @return \BW\BlogBundle\Entity\CustomField 
     */
    public function getCustomField()
    {
        return $this->customField;
    }

    /**
     * Add customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     * @return ProductCustomField
     */
    public function addCustomFieldProperty(CustomFieldProperty $customFieldProperties)
    {
        $this->customFieldProperties[] = $customFieldProperties;

        return $this;
    }

    /**
     * Remove customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     */
    public function removeCustomFieldProperty(CustomFieldProperty $customFieldProperties)
    {
        $this->customFieldProperties->removeElement($customFieldProperties);
    }

    /**
     * Get customFieldProperties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomFieldProperties()
    {
        return $this->customFieldProperties;
    }
}
