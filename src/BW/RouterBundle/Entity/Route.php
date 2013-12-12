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
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * The controller name (a string like BlogBundle:Post:index)
     * @return string
     */
    public function getController() {
        
        return $this->defaults['_controller'];
    }
    
    
    public function __construct() {
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
}