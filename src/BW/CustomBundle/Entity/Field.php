<?php

namespace BW\CustomBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Field
 * @package BW\CustomBundle\Entity
 */
class Field
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
    private $properties;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
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
     * @return Field
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
     * @return Field
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
     * @return Field
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
     * @return Field
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
     * Add properties
     *
     * @param \BW\CustomBundle\Entity\Property $properties
     * @return Field
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
