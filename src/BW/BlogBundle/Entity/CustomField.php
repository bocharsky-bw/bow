<?php

namespace BW\BlogBundle\Entity;

use BW\ShopBundle\Entity\ProductCustomField;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CustomField
 * @package BW\BlogBundle\Entity
 */
class CustomField
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var boolean
     */
    private $expanded = true;

    /**
     * @var boolean
     */
    private $multiple = true;

    /**
     * @var bool
     */
    private $used = true;

    /**
     * @var ArrayCollection
     */
    private $customFieldProperties;

    /**
     * @var ArrayCollection
     */
    private $productCustomFields;

    /**
     * @var ArrayCollection
     */
    private $postCustomFields;

    
    /**
     * The constructor
     */
    public function __construct()
    {
        $this->customFieldProperties = new ArrayCollection();
        $this->productCustomFields = new ArrayCollection();
        $this->postCustomFields = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function __toString() 
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return CustomField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set expanded
     *
     * @param boolean $expanded
     * @return CustomField
     */
    public function setExpanded($expanded)
    {
        $this->expanded = $expanded;

        return $this;
    }

    /**
     * Get expanded
     *
     * @return boolean
     */
    public function getExpanded()
    {
        return $this->expanded;
    }

    /**
     * Get expanded
     *
     * @return boolean
     */
    public function isExpanded()
    {
        return $this->expanded;
    }

    /**
     * Set multiple
     *
     * @param boolean $multiple
     * @return CustomField
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * Get multiple
     *
     * @return boolean
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Get multiple
     *
     * @return boolean
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * Set used
     *
     * @param boolean $used
     * @return CustomField
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Get used
     *
     * @return boolean
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * Add customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     * @return CustomField
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

    /**
     * Add productCustomFields
     *
     * @param \BW\ShopBundle\Entity\ProductCustomField $productCustomFields
     * @return CustomField
     */
    public function addProductCustomField(ProductCustomField $productCustomFields)
    {
        $this->productCustomFields[] = $productCustomFields;

        return $this;
    }

    /**
     * Remove productCustomFields
     *
     * @param \BW\ShopBundle\Entity\ProductCustomField $productCustomFields
     */
    public function removeProductCustomField(ProductCustomField $productCustomFields)
    {
        $this->productCustomFields->removeElement($productCustomFields);
    }

    /**
     * Get productCustomFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductCustomFields()
    {
        return $this->productCustomFields;
    }

    /**
     * Add postCustomFields
     *
     * @param \BW\BlogBundle\Entity\PostCustomField $postCustomFields
     * @return CustomField
     */
    public function addPostCustomField(PostCustomField $postCustomFields)
    {
        $this->postCustomFields[] = $postCustomFields;

        return $this;
    }

    /**
     * Remove postCustomFields
     *
     * @param \BW\BlogBundle\Entity\PostCustomField $postCustomFields
     */
    public function removePostCustomField(PostCustomField $postCustomFields)
    {
        $this->postCustomFields->removeElement($postCustomFields);
    }

    /**
     * Get postCustomFields
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostCustomFields()
    {
        return $this->postCustomFields;
    }
}
