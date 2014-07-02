<?php

namespace BW\MailingBundle\Entity;

use BW\UserBundle\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Message
 * @package BW\MailingBundle\Entity
 */
class Message
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var boolean $sending
     */
    private $sending = false;

    /**
     * @var string $subject
     */
    private $subject = '';

    /**
     * @var string $text
     */
    private $text = '';

    /**
     * @var \DateTime $created
     */
    private $created;

    /**
     * @var ArrayCollection $roles
     */
    private $mailing;

    /**
     * @var ArrayCollection $roles
     */
    private $roles;



    public function __construct() {
        $this->created = new \DateTime;
        $this->mailing = new ArrayCollection();
        $this->roles = new ArrayCollection();
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
     * Set sending
     *
     * @param boolean $sending
     * @return Message
     */
    public function setSending($sending)
    {
        $this->sending = $sending;

        return $this;
    }

    /**
     * Get sending
     *
     * @return boolean
     */
    public function getSending()
    {
        return $this->sending;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Message
     */
    public function setCreated(\DateTime $created)
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
     * Add roles
     *
     * @param \BW\UserBundle\Entity\Role $roles
     * @return Message
     */
    public function addRole(Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \BW\UserBundle\Entity\Role $roles
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add mailing
     *
     * @param \BW\MailingBundle\Entity\Mailing $mailing
     * @return Message
     */
    public function addMailing(Mailing $mailing)
    {
        $this->mailing[] = $mailing;
        return $this;
    }

    /**
     * Remove mailing
     *
     * @param \BW\MailingBundle\Entity\Mailing $mailing
     */
    public function removeMailing(Mailing $mailing)
    {
        $this->mailing->removeElement($mailing);
    }

    /**
     * Get mailing
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMailing()
    {
        return $this->mailing;
    }
}