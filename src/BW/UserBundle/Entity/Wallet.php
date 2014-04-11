<?php

namespace BW\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BW\UserBundle\Entity\Wallet
 *
 * @ORM\Table(name="wallets")
 * @ORM\Entity(repositoryClass="BW\UserBundle\Entity\WalletRepository")
 */
class Wallet
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
     * @var decimal $totalAmount
     *
     * @ORM\Column(name="total_amount", type="decimal", precision=10, scale=4)
     */
    private $totalAmount;

    /**
     * @ORM\ManyToOne(targetEntity="Profile", inversedBy="wallets")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    private $profile;

    /**
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="wallets")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;
    
    
    public function __construct() {
        $this->totalAmount = 0;
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
     * Set totalAmount
     *
     * @param decimal $totalAmount
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
     * @return decimal 
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * Set profile
     *
     * @param BW\UserBundle\Entity\Profile $profile
     * @return Wallet
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
     * @return Wallet
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