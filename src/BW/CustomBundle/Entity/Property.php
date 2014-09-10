<?php

namespace BW\CustomBundle\Entity;

/**
 * Class Property
 * @package BW\CustomBundle\Entity
 */
class Property
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
     * @var Field $field
     */
    private $field;

    
    /**
     * The constructor
     */
    public function __construct()
    {
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
     * @return Property
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
     * Set field
     *
     * @param \BW\CustomBundle\Entity\Field $field
     * @return Property
     */
    public function setField(Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \BW\CustomBundle\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }
}
