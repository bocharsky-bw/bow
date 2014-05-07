<?php

namespace BW\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomFieldProperty
 *
 * @ORM\Table(name="custom_field_properties")
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
     * @ORM\ManyToOne(targetEntity="customField", inversedBy="customFieldProperties")
     * @ORM\JoinColumn(name="custom_field_id", referencedColumnName="id")
     */
    private $customField;

    
    
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
     * @param \BW\BlogBundle\Entity\customField $customField
     * @return CustomFieldProperty
     */
    public function setCustomField(\BW\BlogBundle\Entity\customField $customField = null)
    {
        $this->customField = $customField;

        return $this;
    }

    /**
     * Get customField
     *
     * @return \BW\BlogBundle\Entity\customField 
     */
    public function getCustomField()
    {
        return $this->customField;
    }
}
