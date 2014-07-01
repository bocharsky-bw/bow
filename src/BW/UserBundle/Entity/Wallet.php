<?php

namespace BW\UserBundle\Entity;

/**
 * Class Wallet
 * @package BW\UserBundle\Entity
 */
class Wallet
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $totalAmount
     */
    private $totalAmount = 0.00;

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var Currency
     */
    private $currency;


    /**
     * The constructor
     */
    public function __construct()
    {
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
     * Set totalAmount
     *
     * @param float $totalAmount
     * @return Wallet
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    /**
     * Get totalAmount
     *
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set profile
     *
     * @param \BW\UserBundle\Entity\Profile $profile
     * @return Wallet
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
     * @return Wallet
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
}
