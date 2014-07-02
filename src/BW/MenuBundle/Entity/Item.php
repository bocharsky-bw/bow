<?php

namespace BW\MenuBundle\Entity;

use BW\LocalizationBundle\Entity\Lang;
use BW\RouterBundle\Entity\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Constraints as Assert;
use BW\BlogBundle\Entity\Image;

/**
 * Class Item
 * @package BW\MenuBundle\Entity
 */
class Item
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name = '';

    /**
     * @var string $title
     */
    private $title = '';

    /**
     * @var string $href
     */
    private $href = '';

    /**
     * @var string $class
     */
    private $class = '';

    /**
     * @var boolean $blank
     */
    private $blank = false;

    /**
     * @var integer $ordering
     * @TODO Rename to "order"
     */
    private $ordering = 0;

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
     * @var Menu $menu
     */
    private $menu;
    
    /**
     * @var Lang $lang
     */
    private $lang;

    /**
     * @var Item $parent
     */
    private $parent;

    /**
     * @var ArrayCollection $children
     */
    private $children;

    /**
     * @var Route $route
     */
    private $route;

    /**
     * @var Image $image
     */
    private $image;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    
    /**
     * Generate current nested level
     * 
     * ORM\PrePersist
     * ORM\PreUpdate
     * @return integer
     */
    public function generateLevel() {
        $this->level = 0;
        $parent = $this->getParent();
        
        while ($parent) {
            $this->level++;
            $parent = $parent->getParent();
        }
        
        return $this;
    }
    
    
    public function __toString()
    {
        return str_repeat('- ', $this->getLevel()). $this->getName();
    }

    /**
     * Set default values
     * 
     * ORM\PrePersist
     * @param LifecycleEventArgs $args
     * @return Item
     */
    public function setDefaultValues(LifecycleEventArgs $args) {
        $values = array(
            'title' => '',
            'href' => '',
            'class' => '',
        );
        
        $item = $args->getEntity();
        $class = __CLASS__;
        if ($item instanceof $class) {
            foreach ($values as $field => $value) {
                $getter = 'get'. ucfirst($field);
                if (method_exists($this, $getter)) {
                    if ($this->$getter() === NULL) {
                        $setter = 'set'. ucfirst($field);
                        if (method_exists($this, $setter)) {
                            $this->$setter($value);
                        }
                    }
                }
            }
        }
        
        return $this;
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
     * @return Item
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
     * Set title
     *
     * @param string $title
     * @return Item
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
     * Set blank
     *
     * @param boolean $blank
     * @return Item
     */
    public function setBlank($blank)
    {
        $this->blank = $blank;
    
        return $this;
    }

    /**
     * Get blank
     *
     * @return boolean 
     */
    public function getBlank()
    {
        return $this->blank;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Item
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
     * Set ordering
     *
     * @param integer $ordering
     * @return Item
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;
    
        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer 
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set menu
     *
     * @param \BW\MenuBundle\Entity\Menu $menu
     * @return Item
     */
    public function setMenu(Menu $menu = null)
    {
        $this->menu = $menu;
    
        return $this;
    }

    /**
     * Get menu
     *
     * @return \BW\MenuBundle\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set parent
     *
     * @param \BW\MenuBundle\Entity\Item $parent
     * @return Item
     */
    public function setParent(Item $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \BW\MenuBundle\Entity\Item 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \BW\MenuBundle\Entity\Item $children
     * @return Item
     */
    public function addChildren(Item $children = null)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \BW\MenuBundle\Entity\Item $children
     */
    public function removeChildren(Item $children =null)
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
     * Set href
     *
     * @param string $href
     * @return Item
     */
    public function setHref($href)
    {
        $this->href = $href;
    
        return $this;
    }

    /**
     * Get href
     *
     * @return string 
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return Item
     */
    public function setClass($class)
    {
        $this->class = $class;
    
        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set lang
     *
     * @param \BW\LocalizationBundle\Entity\Lang $lang
     * @return Item
     */
    public function setLang(Lang $lang = null)
    {
        $this->lang = $lang;
    
        return $this;
    }

    /**
     * Get lang
     *
     * @return \BW\LocalizationBundle\Entity\Lang 
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set left
     *
     * @param integer $left
     * @return Item
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
     * @return Item
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
     * @param \BW\RouterBundle\Entity\Route $route
     * @return $this
     */
    public function setRoute(Route $route = null)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return int
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Add children
     *
     * @param \BW\MenuBundle\Entity\Item $children
     * @return Item
     */
    public function addChild(Item $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \BW\MenuBundle\Entity\Item $children
     */
    public function removeChild(Item $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Set image
     *
     * @param \BW\BlogBundle\Entity\Image $image
     *
     * @return Item
     */
    public function setImage(Image $image = null)
    {
        if (isset($image)) {
            $file = $image->getFile();
            if (isset($file)) {
                $this->image = $image;
            }
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return \BW\BlogBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
}
