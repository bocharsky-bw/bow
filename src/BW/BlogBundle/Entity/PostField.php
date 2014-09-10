<?php

namespace BW\BlogBundle\Entity;

use BW\CustomBundle\Entity\Field;
use BW\CustomBundle\Entity\Property;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PostField
 * @package BW\BlogBundle\Entity
 */
class PostField
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var Post $post
     */
    private $post;

    /**
     * @var Field $field
     */
    private $field;

    /**
     * @var ArrayCollection $properties
     */
    private $properties;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }


    /* GETTERS / SETTERS */

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
     * Set post
     *
     * @param \BW\BlogBundle\Entity\Post $post
     * @return PostField
     */
    public function setPost(Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \BW\BlogBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set field
     *
     * @param \BW\CustomBundle\Entity\Field $field
     * @return PostField
     */
    public function setField(Field $field = null)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return \BW\CustomBundle\Entity\Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Add properties
     *
     * @param \BW\CustomBundle\Entity\Property $properties
     * @return PostField
     */
    public function addProperty(Property $properties)
    {
        $this->properties[] = $properties;

        return $this;
    }

    /**
     * Remove properties
     *
     * @param \BW\CustomBundle\Entity\Property $properties
     */
    public function removeProperty(Property $properties)
    {
        $this->properties->removeElement($properties);
    }

    /**
     * Get properties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
