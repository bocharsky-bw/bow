<?php

namespace BW\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CustomField
 * @package BW\BlogBundle\Entity
 */
class CustomField
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name = '';

    /**
     * @var boolean $expanded
     */
    private $expanded = true;

    /**
     * @var boolean $multiple
     */
    private $multiple = true;

    /**
     * @var ArrayCollection $postCustomFields
     */
    private $postCustomFields;

    /**
     * @var ArrayCollection $customFieldProperties
     */
    private $customFieldProperties;

    
    /**
     * The constructor
     */
    public function __construct()
    {
        $this->postCustomFields = new ArrayCollection();
        $this->customFieldProperties = new ArrayCollection();
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

}
