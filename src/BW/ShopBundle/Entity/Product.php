<?php

namespace BW\ShopBundle\Entity;

use BW\MainBundle\Service\SluggableInterface;
use BW\RouterBundle\Entity\Route;
use BW\RouterBundle\Entity\RouteInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 * @package BW\ShopBundle\Entity
 */
class Product implements SluggableInterface, RouteInterface
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
    private $shortDescription = '';

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

    /**
     * @var \BW\ShopBundle\Entity\Category
     */
    private $category;

    /**
     * @var \BW\RouterBundle\Entity\Route
     */
    private $route;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $productImages;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $productCustomFields;


    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->productImages = new ArrayCollection();
        $this->productCustomFields = new ArrayCollection();
    }


    public function generatePath()
    {
        $slug = $this->getSlug();
        $first = isset($slug[0]) ? $slug[0] : '';

        if (0 !== strcmp('/', $first)) {
            $segments = array();
            $parent = $this->getCategory();

            if ($parent) {
                $segments[] = ''; // Add slash to the end of path
            }

            while ($parent) {
                if ($parent->getSlug()) {
                    $segments[] = $parent->getSlug();
                }
                $parent = $parent->getParent();
            }

            $slug = '/' . implode('/', array_reverse($segments)) . $this->getSlug();
        }

        return $slug;
    }

    public function getDefaults()
    {
        if ( ! $this->getId()) {
            throw new \RuntimeException(''
                . 'The entity ID not defined. '
                . 'Maybe you forgot to execute "flush" method before handle the entity?'
            );
        }

        return array(
            '_controller' => 'BWShopBundle:Product:show',
            'id' => $this->getId(),
        );
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
        if (isset($sku)) {
            $this->sku = $sku;
        } else {
            $this->sku = '';
        }

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
        if (isset($shortDescription)) {
            $this->shortDescription = $shortDescription;
        } else {
            $this->shortDescription = '';
        }

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
     * @return Product
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
     * @return Product
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
     * @return Product
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

    /**
     * Set category
     *
     * @param \BW\ShopBundle\Entity\Category $category
     * @return Product
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \BW\ShopBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set route
     *
     * @param \BW\RouterBundle\Entity\Route $route
     * @return Product
     */
    public function setRoute(Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \BW\RouterBundle\Entity\Route 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Product
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Product
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add productImages
     *
     * @param \BW\ShopBundle\Entity\ProductImage $productImages
     * @return Product
     */
    public function addProductImage(ProductImage $productImages)
    {
        if ($productImages->getImage()) {
            $this->productImages[] = $productImages;
        }

        return $this;
    }

    /**
     * Remove productImages
     *
     * @param \BW\ShopBundle\Entity\ProductImage $productImages
     */
    public function removeProductImage(ProductImage $productImages)
    {
        $this->productImages->removeElement($productImages);
    }

    /**
     * Get productImages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductImages()
    {
        return $this->productImages;
    }

    /**
     * Add productCustomFields
     *
     * @param \BW\ShopBundle\Entity\ProductCustomField $productCustomFields
     * @return Product
     */
    public function addProductCustomField(ProductCustomField $productCustomFields)
    {
        $this->productCustomFields[] = $productCustomFields;

        return $this;
    }

    /**
     * Remove productCustomFields
     *
     * @param \BW\ShopBundle\Entity\ProductCustomField $productCustomFields
     */
    public function removeProductCustomField(ProductCustomField $productCustomFields)
    {
        $this->productCustomFields->removeElement($productCustomFields);
    }

    /**
     * Get productCustomFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductCustomFields()
    {
        return $this->productCustomFields;
    }
}
