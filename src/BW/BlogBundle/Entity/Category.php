<?php

namespace BW\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="BW\BlogBundle\Entity\CategoryRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="heading", type="string", length=255)
     */
    private $heading;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", length=255)
     */
    private $metaDescription;
    
    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="\BW\LocalizationBundle\Entity\Lang")
     * @ORM\JoinColumn(name="lang_id", referencedColumnName="id")
     */
    private $lang;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;
    
    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="\BW\RouterBundle\Entity\Route", cascade={"remove"})
     * @ORM\JoinColumn(name="route_id", referencedColumnName="id")
     */
    private $route;
    
    /**
     * @var integer
     *
     * @ORM\OneToMany(targetEntity="\BW\BlogBundle\Entity\Post", mappedBy="category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $posts;
    
    /**
     * @ORM\OneToMany(targetEntity="\BW\BlogBundle\Entity\Category", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="\BW\BlogBundle\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * ORM\OrderBy({"id" = "ASC"})
     */
    private $parent;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="lft", type="integer")
     */
    private $left;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="rgt", type="integer")
     */
    private $right;
    
    
    /**
     * Текущий уровень вложенности категории
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
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

    public function __toString() {
        return str_repeat('- ', $this->level) . $this->heading;
    }
    
    /**
     * Set default values
     * 
     * @ORM\PrePersist
     * @param array $values
     * @return Category
     */
    public function setDefaultValues(\Doctrine\ORM\Event\LifecycleEventArgs $args) {
        $values = array(
            'slug' => '',
            'title' => '',
            'metaDescription' => '',
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
    

    public function __construct() {
        $this->children = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->level = 0;
        $this->left = 0;
        $this->right = 0;
        $this->published = TRUE;
    }
    


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
     * @return Category
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
     * @return Category
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
     * Set lang
     *
     * @param \BW\LocalizationBundle\Entity\Lang $lang
     * @return Category
     */
    public function setLang(\BW\LocalizationBundle\Entity\Lang $lang = null)
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
     * @return Post
     */
    public function setRoute(\BW\RouterBundle\Entity\Route $route = null)
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
     * Add posts
     *
     * @param \BW\BlogBundle\Entity\Post $posts
     * @return Category
     */
    public function addPost(\BW\BlogBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    
        return $this;
    }

    /**
     * Remove posts
     *
     * @param \BW\BlogBundle\Entity\Post $posts
     */
    public function removePost(\BW\BlogBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add children
     *
     * @param BW\BlogBundle\Entity\Category $children
     * @return Category
     */
    public function addChildren(\BW\BlogBundle\Entity\Category $children)
    {
        $this->children[] = $children;
        return $this;
    }

    /**
     * Remove children
     *
     * @param BW\BlogBundle\Entity\Category $children
     */
    public function removeChildren(\BW\BlogBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param BW\BlogBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\BW\BlogBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return BW\BlogBundle\Entity\Category 
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
     * Add children
     *
     * @param \BW\BlogBundle\Entity\Category $children
     * @return Category
     */
    public function addChild(\BW\BlogBundle\Entity\Category $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \BW\BlogBundle\Entity\Category $children
     */
    public function removeChild(\BW\BlogBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }
}
