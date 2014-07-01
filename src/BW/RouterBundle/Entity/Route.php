<?php

namespace BW\RouterBundle\Entity;

use BW\LocalizationBundle\Entity\Lang;

/**
 * Class Route
 * @package BW\RouterBundle\Entity
 */
class Route
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     *
     * @TODO rename "path" to "uri"
     */
    private $path = '';

    /**
     * @var string
     */
    private $query = '';

    /**
     * @var array
     */
    private $defaults = array();

    /**
     * @var Lang
     */
    private $lang;
    

    /**
     * The controller name (a string like BlogBundle:Post:index)
     *
     * @return string
     */
    public function getController()
    {
        return $this->defaults['_controller'];
    }


    /**
     * The constructor
     */
    public function __construct()
    {
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
     * Set path
     *
     * @param string $path
     * @return Route
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set query
     *
     * @param string $query
     * @return Route
     */
    public function setQuery($query)
    {
        $this->query = $query;
    
        return $this;
    }

    /**
     * Get query
     *
     * @return string 
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set defaults
     *
     * @param array $defaults
     * @return Route
     */
    public function setDefaults($defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * Get defaults
     *
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Set lang
     *
     * @param \BW\LocalizationBundle\Entity\Lang $lang
     * @return Route
     */
    public function setLang(Lang $lang = null)
    {
        $this->lang = $lang;
    
        return $this;
    }

    /**
     * Get lang
     *
     * @return \BW\LocalizationBundle\Entity\Lang 
     */
    public function getLang()
    {
        return $this->lang;
    }
}