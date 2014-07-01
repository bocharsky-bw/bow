<?php

namespace BW\LocalizationBundle\Entity;

/**
 * Class Lang
 * @package BW\LocalizationBundle\Entity
 */
class Lang
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string $name
     */
    private $name = '';

    /**
     * @var string $sign
     */
    private $sign = '';

    /**
     * @var string $locale
     */
    private $locale = '';


    /**
     * The constructor
     */
    public function __construct()
    {
    }
    
    public function __toString()
    {
        return (string)$this->getSign();
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
     * @return Lang
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
     * Set sign
     *
     * @param string $sign
     * @return Lang
     */
    public function setSign($sign)
    {
        $this->sign = $sign;
    
        return $this;
    }

    /**
     * Get sign
     *
     * @return string 
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return Lang
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
