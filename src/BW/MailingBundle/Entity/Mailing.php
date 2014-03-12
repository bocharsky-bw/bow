<?php

namespace BW\MailingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * BW\MailingBundle\Entity\Mailing
 *
 * @ORM\Table(name="mailing")
 * @ORM\Entity(repositoryClass="BW\MailingBundle\Entity\MailingRepository")
 */
class Mailing
{
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var boolean $success
     *
     * @ORM\Column(name="success", type="boolean")
     */
    private $success;
    
    /**
     * @var integer $message
     *
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="mailing")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     */
    private $message;
    
    /**
     * @var integer $user
     *
     * @ORM\ManyToOne(targetEntity="\BW\UserBundle\Entity\User", inversedBy="mailing")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    

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
     * Set success
     *
     * @param boolean $success
     * @return Mailing
     */
    public function setSuccess($success)
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Get success
     *
     * @return boolean 
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Set message
     *
     * @param BW\MailingBundle\Entity\Message $message
     * @return Mailing
     */
    public function setMessage(\BW\MailingBundle\Entity\Message $message = null)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return BW\MailingBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set user
     *
     * @param BW\UserBundle\Entity\User $user
     * @return Mailing
     */
    public function setUser(\BW\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return BW\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}