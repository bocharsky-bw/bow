<?php

namespace BW\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomField
 *
 * @ORM\Table(name="custom_fields")
 * @ORM\Entity(repositoryClass="BW\BlogBundle\Entity\CustomFieldRepository")
 */
class CustomField
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name = '';
    
    /**
     * @var integer
     * 
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="customFields")
     */
    private $posts;
    
    /**
     * @var integer
     * 
     * @ORM\OneToMany(targetEntity="CustomFieldProperty", mappedBy="customField")
     */
    private $customFieldProperties;
    
    
    /**
     * The constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customFieldProperties = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function __toString() 
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     * @return CustomField
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
     * Add posts
     *
     * @param \BW\BlogBundle\Entity\Post $posts
     * @return CustomField
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
     * Add customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     * @return CustomField
     */
    public function addCustomFieldProperty(\BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties)
    {
        $this->customFieldProperties[] = $customFieldProperties;

        return $this;
    }

    /**
     * Remove customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     */
    public function removeCustomFieldProperty(\BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties)
    {
        $this->customFieldProperties->removeElement($customFieldProperties);
    }

    /**
     * Get customFieldProperties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomFieldProperties()
    {
        return $this->customFieldProperties;
    }
}
