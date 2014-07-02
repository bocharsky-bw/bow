<?php

namespace BW\MailingBundle\Entity;

use BW\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Mailing
 * @package BW\MailingBundle\Entity
 */
class Mailing
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var boolean $success
     */
    private $success = false;
    
    /**
     * @var Message $message
     */
    private $message;
    
    /**
     * @var User $user
     */
    private $user;

    
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
     * @param \BW\MailingBundle\Entity\Message $message
     * @return Mailing
     */
    public function setMessage(Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \BW\MailingBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set user
     *
     * @param \BW\UserBundle\Entity\User $user
     * @return Mailing
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BW\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}