<?php

namespace BW\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Photo
 * @package BW\GalleryBundle\Entity
 */
class Photo
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
    private $shortDescription;

    /**
     * @var Gallery
     */
    private $gallery;

    private $smallImage;

    private $bigImage;


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
     * @return Photo
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
     * @return Photo
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
     * Set gallery
     *
     * @param \BW\GalleryBundle\Entity\Gallery $gallery
     * @return Photo
     */
    public function setGallery(\BW\GalleryBundle\Entity\Gallery $gallery = null)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \BW\GalleryBundle\Entity\Gallery 
     */
    public function getGallery()
    {
        return $this->gallery;
    }



    /**
     * Set smallImage
     *
     * @param \BW\BlogBundle\Entity\Image $smallImage
     * @return Photo
     */
    public function setSmallImage(\BW\BlogBundle\Entity\Image $smallImage = null)
    {
        if (isset($smallImage)) {
            $file = $smallImage->getFile();
            if (isset($file)) {
                $this->smallImage = $smallImage;
            }
        }

        return $this;
    }

    /**
     * Get smallImage
     *
     * @return \BW\BlogBundle\Entity\Image 
     */
    public function getSmallImage()
    {
        return $this->smallImage;
    }

    /**
     * Set bigImage
     *
     * @param \BW\BlogBundle\Entity\Image $bigImage
     * @return Photo
     */
    public function setBigImage(\BW\BlogBundle\Entity\Image $bigImage = null)
    {
        if (isset($bigImage)) {
            $file = $bigImage->getFile();
            if (isset($file)) {
                $this->bigImage = $bigImage;
            }
        }

        return $this;
    }

    /**
     * Get bigImage
     *
     * @return \BW\BlogBundle\Entity\Image 
     */
    public function getBigImage()
    {
        return $this->bigImage;
    }
}
