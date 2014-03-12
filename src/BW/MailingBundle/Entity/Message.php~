<?php

namespace BW\MailingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BW\MailingBundle\Entity\Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="BW\MailingBundle\Entity\MessageRepository")
 */
class Message
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
     * @var boolean $sending
     *
     * @ORM\Column(name="sending", type="boolean")
     */
    private $sending;
    
    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var string $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    /**
     * @var integer $roles
     *
     * @ORM\ManyToMany(targetEntity="\BW\UserBundle\Entity\Role", inversedBy="messages")
     * @ORM\JoinTable(name="message_role")
     */
    private $roles;
    
    /**
     * @var integer $roles
     *
     * @ORM\OneToMany(targetEntity="Mailing", mappedBy="message")
     */
    private $mailing;
    
    
    
    public function __construct() {
        $this->sending = FALSE;
        $this->created = new \DateTime;
        
        $this->roles = new ArrayCollection();
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
     * @param text $text
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
     * @return text 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set created
     *
     * @param datetime $created
     * @return Message
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return datetime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Add roles
     *
     * @param BW\UserBundle\Entity\Role $roles
     * @return Message
     */
    public function addRole(\BW\UserBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
        return $this;
    }

    /**
     * Remove roles
     *
     * @param BW\UserBundle\Entity\Role $roles
     */
    public function removeRole(\BW\UserBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add mailing
     *
     * @param BW\MailingBundle\Entity\Mailing $mailing
     * @return Message
     */
    public function addMailing(\BW\MailingBundle\Entity\Mailing $mailing)
    {
        $this->mailing[] = $mailing;
        return $this;
    }

    /**
     * Remove mailing
     *
     * @param BW\MailingBundle\Entity\Mailing $mailing
     */
    public function removeMailing(\BW\MailingBundle\Entity\Mailing $mailing)
    {
        $this->mailing->removeElement($mailing);
    }

    /**
     * Get mailing
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMailing()
    {
        return $this->mailing;
    }
}