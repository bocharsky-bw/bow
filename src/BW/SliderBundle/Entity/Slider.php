<?php

namespace BW\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BW\SliderBundle\Entity\Slider
 *
 * @ORM\Table(name="sliders")
 * @ORM\Entity(repositoryClass="BW\SliderBundle\Entity\SliderRepository")
 */
class Slider
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $alias
     *
     * @ORM\Column(name="alias", type="string", length=255)
     */
    private $alias;
    
    /**
     * @ORM\OneToMany(targetEntity="Slide", mappedBy="slider", cascade={"remove"})
     */
    private $slides;
    
    
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
     * Set name
     *
     * @param string $name
     * @return Slider
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
     * Set alias
     *
     * @param string $alias
     * @return Slider
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Add slides
     *
     * @param BW\SliderBundle\Entity\Slide $slides
     * @return Slider
     */
    public function addSlide(\BW\SliderBundle\Entity\Slide $slides)
    {
        $this->slides[] = $slides;
        return $this;
    }

    /**
     * Remove slides
     *
     * @param BW\SliderBundle\Entity\Slide $slides
     */
    public function removeSlide(\BW\SliderBundle\Entity\Slide $slides)
    {
        $this->slides->removeElement($slides);
    }

    /**
     * Get slides
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSlides()
    {
        return $this->slides;
    }
}