<?php

namespace BW\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 * @package BW\ShopBundle\Entity
 */
class Product
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $published = true;

    /**
     * @var boolean
     */
    private $recent = false;

    /**
     * @var boolean
     */
    private $featured = false;

    /**
     * @var boolean
     */
    private $popular = false;

    /**
     * @var string
     */
    private $sku = '';

    /**
     * @var string
     */
    private $price = 0.00;

    /**
     * @var string
     */
    private $heading = '';

    /**
     * @var string
     */
    private $shortDescription;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $slug = '';

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $metaDescription = '';

    /**
     * @var \BW\ShopBundle\Entity\Vendor
     */
    private $vendor;


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
     * Set published
     *
     * @param boolean $published
     * @return Product
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set recent
     *
     * @param boolean $recent
     * @return Product
     */
    public function setRecent($recent)
    {
        $this->recent = $recent;

        return $this;
    }

    /**
     * Get recent
     *
     * @return boolean
     */
    public function getRecent()
    {
        return $this->recent;
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     * @return Product
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set popular
     *
     * @param boolean $popular
     * @return Product
     */
    public function setPopular($popular)
    {
        $this->popular = $popular;

        return $this;
    }

    /**
     * Get popular
     *
     * @return boolean
     */
    public function getPopular()
    {
        return $this->popular;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set heading
     *
     * @param string $heading
     * @return Product
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get heading
     *
     * @return string 
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Product
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
     * Set description
     *
     * @param string $description
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Product
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set vendor
     *
     * @param \BW\ShopBundle\Entity\Vendor $vendor
     * @return Product
     */
    public function setVendor(Vendor $vendor = null)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return \BW\ShopBundle\Entity\Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }
}
