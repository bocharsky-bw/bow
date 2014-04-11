<?php

namespace BW\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BW\UserBundle\Entity\Replenishment
 *
 * @ORM\Table(name="replenishments")
 * @ORM\Entity(repositoryClass="BW\UserBundle\Entity\ReplenishmentRepository")
 */
class Replenishment
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
     * @var decimal $additiveAmount
     *
     * @ORM\Column(name="additive_amount", type="decimal", precision=10, scale=4)
     */
    private $additiveAmount;

    /**
     * @var decimal $equivalentAmount
     *
     * @ORM\Column(name="equivalent_amount", type="decimal", precision=10, scale=4)
     */
    private $equivalentAmount;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean $confirmed
     *
     * @ORM\Column(name="confirmed", type="boolean")
     */
    private $confirmed;
    
    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="replenishments")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="replenishments")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;
    
    
    public function __construct() {
        $this->additiveAmount = 0;
        $this->equivalentAmount = 0;
        $this->confirmed = FALSE;
        $this->created = new \DateTime;
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
     * Set additiveAmount
     *
     * @param decimal $additiveAmount
     * @return Replenishment
     */
    public function setAdditiveAmount($additiveAmount)
    {
        $this->additiveAmount = $additiveAmount;
        return $this;
    }

    /**
     * Get additiveAmount
     *
     * @return decimal 
     */
    public function getAdditiveAmount()
    {
        return $this->additiveAmount;
    }

    /**
     * Set equivalentAmount
     *
     * @param decimal $equivalentAmount
     * @return Replenishment
     */
    public function setEquivalentAmount($equivalentAmount)
    {
        $this->equivalentAmount = $equivalentAmount;
        return $this;
    }

    /**
     * Get equivalentAmount
     *
     * @return decimal 
     */
    public function getEquivalentAmount()
    {
        return $this->equivalentAmount;
    }

    /**
     * Set created
     *
     * @param datetime $created
     * @return Replenishment
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
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return Replenishment
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set profile
     *
     * @param BW\UserBundle\Entity\Profile $profile
     * @return Replenishment
     */
    public function setProfile(\BW\UserBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * Get profile
     *
     * @return BW\UserBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set currency
     *
     * @param BW\UserBundle\Entity\Currency $currency
     * @return Replenishment
     */
    public function setCurrency(\BW\UserBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * Get currency
     *
     * @return BW\UserBundle\Entity\Currency 
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}