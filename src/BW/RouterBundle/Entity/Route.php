<?php

namespace BW\RouterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Route
 *
 * @ORM\Table(name="routes")
 * @ORM\Entity(repositoryClass="BW\RouterBundle\Entity\RouteRepository")
 */
class Route
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
     * @ORM\Column(name="path", type="string", length=255, unique=true)
     */
    private $path;

    /**
     * @var array
     *
     * @ORM\Column(name="defaults", type="array")
     */
    private $defaults;

    /**
     * @var string
     *
     * @ORM\Column(name="query", type="string", length=255)
     */
    private $query;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="\BW\LocalizationBundle\Entity\Lang")
     * @ORM\JoinColumn(name="lang_id", referencedColumnName="id")
     */
    private $lang;
    

    /**
     * The controller name (a string like BlogBundle:Post:index)
     * @return string
     */
    public function getController() {
        
        return $this->defaults['_controller'];
    }
    
    
    public function __construct() {
        $this->path = '';
        $this->query = '';
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
     * Set lang
     *
     * @param \BW\LocalizationBundle\Entity\Lang $lang
     * @return Route
     */
    public function setLang(\BW\LocalizationBundle\Entity\Lang $lang = null)
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