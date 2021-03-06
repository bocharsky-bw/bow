<?php

namespace BW\ShopBundle\Entity;

use BW\MainBundle\Service\SluggableInterface;
use BW\UploadBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Vendor
 * @package BW\ShopBundle\Entity
 */
class Vendor implements SluggableInterface
{
    /**
     * The upload dir.
     */
    const UPLOAD_DIR = 'vendors';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $heading = '';

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $products;

    /**
     * @var \BW\UploadBundle\Entity\Image
     */
    private $image;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getStringForSlug()
    {
        return $this->getHeading();
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
     * Set heading
     *
     * @param string $heading
     * @return Vendor
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
     * Set description
     *
     * @param string $description
     * @return Vendor
     */
    public function setDescription($description)
    {
        if (isset($description)) {
            $this->description = $description;
        } else {
            $this->description = '';
        }

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
     * @return Vendor
     */
    public function setSlug($slug)
    {
        if (isset($slug)) {
            $this->slug = $slug;
        } else {
            $this->slug = '';
        }

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
     * @return Vendor
     */
    public function setTitle($title)
    {
        if (isset($title)) {
            $this->title = $title;
        } else {
            $this->title = '';
        }

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
     * @return Vendor
     */
    public function setMetaDescription($metaDescription)
    {
        if (isset($metaDescription)) {
            $this->metaDescription = $metaDescription;
        } else {
            $this->metaDescription = '';
        }

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
     * Add products
     *
     * @param \BW\ShopBundle\Entity\Product $products
     * @return Vendor
     */
    public function addProduct(Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \BW\ShopBundle\Entity\Product $products
     */
    public function removeProduct(Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set image
     *
     * @param \BW\UploadBundle\Entity\Image $image
     * @return Vendor
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        if (isset($image)) {
            if (null === $image->getFile()) {
                $this->image = null; // clear image if file not uploaded
            } else {
                $this->image->setSubFolder(self::UPLOAD_DIR);
            }
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return \BW\UploadBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
}
