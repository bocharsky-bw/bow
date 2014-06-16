<?php

namespace BW\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CustomFieldProperty
 *
 * @ORM\Table(name="customfieldproperties")
 * @ORM\Entity(repositoryClass="BW\BlogBundle\Entity\CustomFieldPropertyRepository")
 */
class CustomFieldProperty
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
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="CustomField", inversedBy="customFieldProperties")
     * @ORM\JoinColumn(name="custom_field_id", referencedColumnName="id")
     */
    private $customField;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="BW\BlogBundle\Entity\PostCustomField", mappedBy="customFieldProperties")
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
    public function setCustomField(\BW\BlogBundle\Entity\CustomField $customField = null)
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
