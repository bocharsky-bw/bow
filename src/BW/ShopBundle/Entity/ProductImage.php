<?php

namespace BW\ShopBundle\Entity;

use BW\UploadBundle\Entity\Image;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProductImage
 * @package BW\ShopBundle\Entity
 */
class ProductImage
{
    /**
     * The upload dir.
     */
    const UPLOAD_DIR = 'products';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BW\UploadBundle\Entity\Image
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
     * @param \BW\UploadBundle\Entity\Image $image
     * @return ProductImage
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
