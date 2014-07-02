<?php

namespace BW\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CustomFieldProperty
 * @package BW\BlogBundle\Entity
 */
class CustomFieldProperty
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
     * @var CustomField $customField
     */
    private $customField;

    /**
     * @var ArrayCollection $postCustomFields
     */
    private $postCustomFields;

    
    /**
     * The constructor
     */
    public function __construct()
    {
        $this->postCustomFields = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->name;
    }


    /* SETTERS / GETTERS */
    
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
     * @return CustomFieldProperty
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
     * Set customField
     *
     * @param \BW\BlogBundle\Entity\CustomField $customField
     * @return CustomFieldProperty
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
     * Add postCustomFields
     *
     * @param \BW\BlogBundle\Entity\PostCustomField $postCustomFields
     * @return CustomFieldProperty
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
