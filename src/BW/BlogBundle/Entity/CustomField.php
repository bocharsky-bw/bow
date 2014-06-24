<?php

namespace BW\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CustomField
 *
 * @ORM\Table(name="custom_fields")
 * @ORM\Entity(repositoryClass="BW\BlogBundle\Entity\CustomFieldRepository")
 */
class CustomField
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="expanded", type="boolean")
     */
    private $expanded = true;

    /**
     * @var bool
     *
     * @ORM\Column(name="multiple", type="boolean")
     */
    private $multiple = true;

    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="CustomFieldProperty", mappedBy="customField")
     */
    private $customFieldProperties;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BW\BlogBundle\Entity\PostCustomField", mappedBy="customField")
     */
    private $postCustomFields;
    
    
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
     * Add customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     * @return CustomField
     */
    public function addCustomFieldProperty(\BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties)
    {
        $this->customFieldProperties[] = $customFieldProperties;

        return $this;
    }

    /**
     * Remove customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     */
    public function removeCustomFieldProperty(\BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties)
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
     * Add postCustomFields
     *
     * @param \BW\BlogBundle\Entity\PostCustomField $postCustomFields
     * @return CustomField
     */
    public function addPostCustomField(\BW\BlogBundle\Entity\PostCustomField $postCustomFields)
    {
        $this->postCustomFields[] = $postCustomFields;

        return $this;
    }

    /**
     * Remove postCustomFields
     *
     * @param \BW\BlogBundle\Entity\PostCustomField $postCustomFields
     */
    public function removePostCustomField(\BW\BlogBundle\Entity\PostCustomField $postCustomFields)
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
