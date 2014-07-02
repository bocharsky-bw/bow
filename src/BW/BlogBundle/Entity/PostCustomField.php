<?php

namespace BW\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PostCustomField
 * @package BW\BlogBundle\Entity
 */
class PostCustomField
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
     * @var CustomField $customField
     */
    private $customField;

    /**
     * @var ArrayCollection $customFieldProperties
     */
    private $customFieldProperties;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->customFieldProperties = new ArrayCollection();
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
     * @return PostCustomField
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
     * Set customField
     *
     * @param \BW\BlogBundle\Entity\CustomField $customField
     * @return PostCustomField
     */
    public function setCustomField(CustomField $customField = null)
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
     * Add customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     * @return PostCustomField
     */
    public function addCustomFieldProperty(CustomFieldProperty $customFieldProperties)
    {
        $this->customFieldProperties[] = $customFieldProperties;

        return $this;
    }

    /**
     * Remove customFieldProperties
     *
     * @param \BW\BlogBundle\Entity\CustomFieldProperty $customFieldProperties
     */
    public function removeCustomFieldProperty(CustomFieldProperty $customFieldProperties)
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
