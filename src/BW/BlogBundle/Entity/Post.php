<?php

namespace BW\BlogBundle\Entity;

use BW\LocalizationBundle\Entity\Lang;
use BW\RouterBundle\Entity\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class Post
 * @package BW\BlogBundle\Entity
 */
class Post
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $heading
     */
    private $heading;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string$metaDescription
     */
    private $metaDescription;

    /**
     * @var string $shortDescription
     */
    private $shortDescription;

    /**
     * @var string $content
     * @TODO Rename to "description"
     */
    private $content;

    /**
     * @var \DateTime $created
     */
    private $created;

    /**
     * @var \DateTime $updated
     */
    private $updated;

    /**
     * @var boolean $published
     */
    private $published = true;

    /**
     * @var boolean $home
     */
    private $home = false;

    /**
     * @var Category $category
     */
    private $category;

    /**
     * @var Lang $lang
     */
    private $lang;

    /**
     * @var Route $route
     */
    private $route;

    /**
     * @var ArrayCollection $postCustomFields
     */
    private $postCustomFields;

    /**
     * @var Image
     */
    private $image;


    public function __construct()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->postCustomFields = new ArrayCollection();
    }


    /**
     * Set default values
     *
     * ORM\PrePersist
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return $this
     */
    public function setDefaultValues(LifecycleEventArgs $args)
    {
        $values = array(
            'slug' => '',
            'title' => '',
            'metaDescription' => '',
            'shortDescription' => '',
            'content' => '',
        );

        $item = $args->getEntity();
        $class = __CLASS__;
        if ($item instanceof $class) {
            foreach ($values as $field => $value) {
                $getter = 'get'. ucfirst($field);
                if (method_exists($this, $getter)) {
                    if ($this->$getter() === null) {
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
     * Set heading
     *
     * @param string $heading
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Post
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
     * Set content
     *
     * @param string $content
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Post
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
     * @return Post
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
     * Set published
     *
     * @param boolean $published
     * @return Post
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
     * Is home
     *
     * @return boolean
     */
    public function isHome()
    {
        return $this->home;
    }

    /**
     * Get home
     *
     * @return boolean
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Set lang
     *
     * @param Lang $lang
     * @return Post
     */
    public function setLang(Lang $lang = null)
    {
        $this->lang = $lang;
    
        return $this;
    }

    /**
     * Get lang
     *
     * @return Lang
     */
    public function getLang()
    {
        return $this->lang;
    }
    

    /**
     * Set route
     *
     * @param \BW\RouterBundle\Entity\Route $route
     * @return Post
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
     * Set category
     *
     * @param \BW\BlogBundle\Entity\Category $category
     * @return Post
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \BW\BlogBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set home
     *
     * @param boolean $home
     * @return Post
     */
    public function setHome($home)
    {
        $this->home = $home;
        
        return $this;
    }

    /**
     * Set image
     *
     * @param \BW\BlogBundle\Entity\Image $image
     *
     * @return Post
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

    /**
     * Add postCustomFields
     *
     * @param \BW\BlogBundle\Entity\PostCustomField $postCustomFields
     * @return Post
     */
    public function addPostCustomField(PostCustomField $postCustomFields)
    {
        $this->postCustomFields[] = $postCustomFields;

        return $this;
    }

    /**
     * Remove postCustomFields
     *
     * @param \BW\BlogBundle\Entity\PostCustomField $postCustomFields
     */
    public function removePostCustomField(PostCustomField $postCustomFields)
    {
        $this->postCustomFields->removeElement($postCustomFields);
    }

    /**
     * Get postCustomFields
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostCustomFields()
    {
        return $this->postCustomFields;
    }

}
