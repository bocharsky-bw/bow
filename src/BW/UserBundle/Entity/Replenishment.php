<?php

namespace BW\UserBundle\Entity;

/**
 * Class Replenishment
 * @package BW\UserBundle\Entity
 */
class Replenishment
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $additiveAmount
     */
    private $additiveAmount = 0.00;

    /**
     * @var float $equivalentAmount
     */
    private $equivalentAmount = 0.00;

    /**
     * @var \DateTime $created
     */
    private $created;

    /**
     * @var integer $status
     * replenishment_statuses в BW/UserBundle/Resources/config/config.yml
     */
    private $status = 0;
    
    /**
     * @var Profile $profile
     */
    private $profile;

    /**
     * @var Currency $currency
     */
    private $currency;
    
    /**
     * @var Receipt $receipt
     */
    private $receipt;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime;
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
     * Set additiveAmount
     *
     * @param float $additiveAmount
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
     * @return float
     */
    public function getAdditiveAmount()
    {
        return $this->additiveAmount;
    }

    /**
     * Set equivalentAmount
     *
     * @param float $equivalentAmount
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
     * @return float
     */
    public function getEquivalentAmount()
    {
        return $this->equivalentAmount;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Replenishment
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
     * Set status
     *
     * @param integer $status
     * @return Replenishment
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set profile
     *
     * @param \BW\UserBundle\Entity\Profile $profile
     * @return Replenishment
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

    /**
     * Set currency
     *
     * @param \BW\UserBundle\Entity\Currency $currency
     * @return Replenishment
     */
    public function setCurrency(Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \BW\UserBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set receipt
     *
     * @param \BW\UserBundle\Entity\Receipt $receipt
     * @return Replenishment
     */
    public function setReceipt(Receipt $receipt = null)
    {
        $this->receipt = $receipt;

        return $this;
    }

    /**
     * Get receipt
     *
     * @return \BW\UserBundle\Entity\Receipt 
     */
    public function getReceipt()
    {
        return $this->receipt;
    }
}
