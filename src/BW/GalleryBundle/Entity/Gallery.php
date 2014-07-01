<?php

namespace BW\GalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Gallery
 * @package BW\GalleryBundle\Entity
 */
class Gallery
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
     * @var string
     */
    private $shortDescription = '';

    /**
     * @var ArrayCollection
     */
    private $photos;


    public function __construct()
    {
        $this->photos = new ArrayCollection();
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
     * @return Gallery
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Gallery
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }


    /**
     * Add photos
     *
     * @param \BW\GalleryBundle\Entity\Photo $photos
     * @return Gallery
     */
    public function addPhoto(\BW\GalleryBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \BW\GalleryBundle\Entity\Photo $photos
     */
    public function removePhoto(\BW\GalleryBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}
