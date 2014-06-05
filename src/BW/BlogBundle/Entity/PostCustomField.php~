<?php

namespace BW\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostCustomField
 *
 * @ORM\Table(name="post_custom_field")
 * @ORM\Entity(repositoryClass="BW\BlogBundle\Entity\PostCustomFieldRepository")
 */
class PostCustomField
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
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="BW\BlogBundle\Entity\Post", inversedBy="postCustomFields")
     */
    private $post;

    /**
     * @var CustomField
     *
     * @ORM\ManyToOne(targetEntity="BW\BlogBundle\Entity\CustomField", inversedBy="postCustomFields")
     * @ORM\JoinColumn(name="custom_field_id", referencedColumnName="id")
     */
    private $customField;


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
    public function setPost(\BW\BlogBundle\Entity\Post $post = null)
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
}
