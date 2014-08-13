<?php

namespace BW\ShopBundle\Entity;

use BW\FileBundle\Entity\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProductImage
 * @package BW\ShopBundle\Entity
 */
class ProductImage
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BW\FileBundle\Entity\Image
     */
    private $image;

    /**
     * @var \BW\ShopBundle\Entity\Product
     */
    private $product;


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
     * Set image
     *
     * @param \BW\FileBundle\Entity\Image $image
     * @return ProductImage
     */
    public function setImage(Image $image = null)
    {
        if ($image->getFile()) {
            $this->image = $image;
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return \BW\FileBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set product
     *
     * @param \BW\ShopBundle\Entity\Product $product
     * @return ProductImage
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \BW\ShopBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
