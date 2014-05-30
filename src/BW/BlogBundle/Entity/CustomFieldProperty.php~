<?php

namespace BW\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomFieldProperty
 *
 * @ORM\Table(name="custom_field_properties")
 * @ORM\Entity(repositoryClass="BW\BlogBundle\Entity\CustomFieldPropertyRepository")
 */
class CustomFieldProperty
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
     * @ORM\ManyToOne(targetEntity="CustomField", inversedBy="customFieldProperties")
     * @ORM\JoinColumn(name="custom_field_id", referencedColumnName="id")
     */
    private $customField;
    
    /**
     * @var integer
     * 
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="customFieldProperties")
     */
    private $posts;

    
    /**
     * The constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return CustomFieldProperty
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
     * Set customField
     *
     * @param \BW\BlogBundle\Entity\CustomField $customField
     * @return CustomFieldProperty
     */
    public function setCustomField(\BW\BlogBundle\Entity\CustomField $customField = null)
    {
        $this->customField = $customField;

        return $this;
    }

    /**
     * Get customField
     *
     * @return \BW\BlogBundle\Entity\CustomField
     */
    public function getCustomField()
    {
        return $this->customField;
    }

    /**
     * Add posts
     *
     * @param \BW\BlogBundle\Entity\Post $posts
     * @return CustomFieldProperty
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
}
