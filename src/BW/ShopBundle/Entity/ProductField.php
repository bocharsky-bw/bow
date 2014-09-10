<?php

namespace BW\ShopBundle\Entity;

use BW\CustomBundle\Entity\Field;
use BW\CustomBundle\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ProductField
 * @package BW\ShopBundle\Entity
 */
class ProductField
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
     * @var Field
     */
    private $field;

    /**
     * @var ArrayCollection
     */
    private $properties;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
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
     * @return ProductField
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
     * @param \BW\CustomBundle\Entity\Field $field
     * @return ProductField
     */
    public function setField(Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get customField
     *
     * @return \BW\CustomBundle\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Add properties
     *
     * @param \BW\CustomBundle\Entity\Property $properties
     * @return ProductField
     */
    public function addProperty(Property $properties)
    {
        $this->properties[] = $properties;

        return $this;
    }

    /**
     * Remove properties
     *
     * @param \BW\CustomBundle\Entity\Property $properties
     */
    public function removeProperty(Property $properties)
    {
        $this->properties->removeElement($properties);
    }

    /**
     * Get properties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
