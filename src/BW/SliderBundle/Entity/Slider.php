<?php

namespace BW\SliderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Slider
 * @package BW\SliderBundle\Entity
 */
class Slider
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $alias
     */
    private $alias;
    
    /**
     * @var ArrayCollection
     */
    private $slides;

    /**
     * @var Group
     */
    private $group;

    
    public function __construct()
    {
        $this->slides = new ArrayCollection();
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
     * @return $this
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
     * @return $this
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
     * @param \BW\SliderBundle\Entity\Slide $slides
     *
     * @return $this
     */
    public function addSlide(Slide $slides)
    {
        $this->slides[] = $slides;

        return $this;
    }

    /**
     * Remove slides
     *
     * @param \BW\SliderBundle\Entity\Slide $slides
     */
    public function removeSlide(Slide $slides)
    {
        $this->slides->removeElement($slides);
    }

    /**
     * Get slides
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * Set group
     *
     * @param \BW\SliderBundle\Entity\Group $group
     * @return Slider
     */
    public function setGroup(Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \BW\SliderBundle\Entity\Group 
     */
    public function getGroup()
    {
        return $this->group;
    }
}
