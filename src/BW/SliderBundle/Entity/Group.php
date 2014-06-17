<?php

namespace BW\SliderBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Group
 * @package BW\SliderBundle\Entity
 */
class Group
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var ArrayCollection
     */
    private $sliders;


    public function __construct()
    {
        $this->sliders = new ArrayCollection();
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
     *
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
     * Set name
     *
     * @param $alias
     *
     * @return $this
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Add sliders
     *
     * @param \BW\SliderBundle\Entity\Slider $sliders
     *
     * @return $this
     */
    public function addSlider(\BW\SliderBundle\Entity\Slider $sliders)
    {
        $this->sliders[] = $sliders;

        return $this;
    }

    /**
     * Remove sliders
     *
     * @param \BW\SliderBundle\Entity\Slider $sliders
     */
    public function removeSlider(\BW\SliderBundle\Entity\Slider $sliders)
    {
        $this->sliders->removeElement($sliders);
    }

    /**
     * Get sliders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSliders()
    {
        return $this->sliders;
    }
}
