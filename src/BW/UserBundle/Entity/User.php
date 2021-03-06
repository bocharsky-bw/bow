<?php

namespace BW\UserBundle\Entity;

use BW\MailingBundle\Entity\Mailing;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class User
 * @package BW\UserBundle\Entity
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string $username
     */
    private $username = '';

    /**
     * @var string $salt
     */
    private $salt = '';

    /**
     * @var string $password
     */
    private $password = '';
    
    /**
     * @var string $email
     */
    private $email = '';

    /**
     * Whether User is active
     *
     * @var boolean $active
     */
    private $active = false;

    /**
     * Whether user e-mail is confirmed
     *
     * @var boolean $confirm
     * @TODO Rename to "confirmed"
     */
    private $confirm = false;

    /**
     * @var \DateTime $created
     */
    private $created;
    
    /**
     * @var \DateTime $updated
     */
    private $updated;

    /**
     * @var string $hash
     */
    private $hash = '';

    /**
     * @var string $facebookId
     */
    private $facebookId = '';

    /**
     * @var string $googleId
     */
    private $googleId = '';

    /**
     * @var string $vkontakteId
     */
    private $vkontakteId = '';

    /**
     * @var ArrayCollection $roles
     */
    private $roles;

    /**
     * @var Profile $profile
     */
    private $profile;

    /**
     * @var ArrayCollection $mailing
     */
    private $mailing;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->salt = md5(uniqid(NULL, TRUE));
        $this->hash = md5(uniqid(NULL, TRUE));
        $this->created = new \DateTime;
        $this->updated = new \DateTime;
        $this->roles = new ArrayCollection();
        $this->mailing = new ArrayCollection();
    }

    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->active;
    }

    public function isConfirmed()
    {
        return $this->confirm;
    }

    /**
     * Generate random 13-symbols user password, unique for every method call
     * 
     * @return string
     */
    public function generateRandomPassword()
    {
        $this->password = uniqid(NULL, FALSE);
        
        return $this->password;
    }

    /**
     * Generate hash for automatic activation link
     * 
     * @return string
     */
    public function generateRandomHash()
    {
        $this->hash = md5(uniqid(NULL, TRUE));
        
        return $this->hash;
    }

    /**
     * Generate date of update
     * 
     * ORM\PreUpdate
     * @return \BW\UserBundle\Entity\User
     */
    public function generateUpdatedDate()
    {
        $this->updated = new \DateTime;
        
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;
    
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Is active
     *
     * @return boolean 
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set confirm
     *
     * @param boolean $confirm
     * @return User
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
    
        return $this;
    }

    /**
     * Get confirm
     *
     * @return boolean 
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * Is confirm
     *
     * @return boolean 
     */
    public function isConfirm()
    {
        return $this->confirm;
    }
    
    /**
     * Add roles
     *
     * @param \BW\UserBundle\Entity\Role $roles
     * @return User
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
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function getRolesCollection()
    {
        return $this->roles;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
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
     * @return User
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
     * Set hash
     *
     * @param string $hash
     * @return User
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    
        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set facebookId
     *
     * @param mixed $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    
        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set googleId
     *
     * @param mixed $googleId
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string 
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Set vkontakteId
     *
     * @param string $vkontakteId
     * @return User
     */
    public function setVkontakteId($vkontakteId)
    {
        $this->vkontakteId = $vkontakteId;

        return $this;
    }

    /**
     * Get vkontakteId
     *
     * @return string 
     */
    public function getVkontakteId()
    {
        return $this->vkontakteId;
    }

    /**
     * Add mailing
     *
     * @param \BW\MailingBundle\Entity\Mailing $mailing
     * @return User
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

    /**
     * Set profile
     *
     * @param \BW\UserBundle\Entity\Profile $profile
     * @return User
     */
    public function setProfile(Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \BW\UserBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
