<?php

namespace BW\ShopBundle\Entity;

use BW\MainBundle\Service\SluggableInterface;
use BW\RouterBundle\Entity\Route;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Category
 * @package BW\ShopBundle\Entity
 */
class Category implements SluggableInterface
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $heading
     */
    private $heading = '';

    /**
     * @var string $slug
     */
    private $slug = '';

    /**
     * @var string $title
     */
    private $title = '';

    /**
     * @var string $metaDescription
     */
    private $metaDescription = '';

    /**
     * @var string $shortDescription
     */
    private $shortDescription = '';

    /**
     * @var string $description
     */
    private $description = '';

    /**
     * @var boolean $published
     */
    private $published = true;

    /**
     * @var integer $order
     */
    private $order = 0;

    /**
     * @var integer $level
     */
    private $level = 0;

    /**
     * @var integer $left
     */
    private $left = 0;

    /**
     * @var integer $right
     */
    private $right = 0;

    /**
     * @var \BW\ShopBundle\Entity\Category $parent
     */
    private $parent;

    /**
     * @var ArrayCollection $children
     */
    private $children;

    /**
     * @var \BW\RouterBundle\Entity\Route $route
     */
    private $route;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $products;


    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
    }


    public function __toString()
    {
        return str_repeat('- ', $this->level) . $this->heading;
    }

    /**
     * Generate current nested level
     *
     * ORM\PrePersist
     * ORM\PreUpdate
     * @return integer
     */
    public function generateLevel()
    {
        $this->level = 0;
        $parent = $this->getParent();

        while ($parent) {
            $this->level++;
            $parent = $parent->getParent();
        }

        return $this;
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
     * @return Category
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
     * Set slug
     *
     * @param string $slug
     * @return Category
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
     * @return Category
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
     * @return Category
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Category
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
     * @return Category
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
     * Set published
     *
     * @param boolean $published
     * @return Category
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
     * Set route
     *
     * @param \BW\RouterBundle\Entity\Route $route
     * @return Category
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
     * Add children
     *
     * @param \BW\ShopBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \BW\ShopBundle\Entity\Category $children
     */
    public function removeChild(Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \BW\ShopBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return \BW\ShopBundle\Entity\Category
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Category
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set left
     *
     * @param integer $left
     * @return Category
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    /**
     * Get left
     *
     * @return integer 
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set right
     *
     * @param integer $right
     * @return Category
     */
    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }

    /**
     * Get right
     *
     * @return integer 
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Category
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Add products
     *
     * @param \BW\ShopBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\BW\ShopBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \BW\ShopBundle\Entity\Product $products
     */
    public function removeProduct(\BW\ShopBundle\Entity\Product $products)
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
}
