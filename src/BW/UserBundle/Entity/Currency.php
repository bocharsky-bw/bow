<?php

namespace BW\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Currency
 * @package BW\UserBundle\Entity
 */
class Currency
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name = '';
    
    /**
     * @var string $abbr
     */
    private $abbr = '';

    /**
     * @var string $symbol
     */
    private $symbol = '';

    /**
     * @var string $alpha3
     */
    private $alpha3 = '';
    
    /**
     * @var integer $numericCode
     */
    private $numericCode = 0;

    /**
     * @var float $exchangeRate
     */
    private $exchangeRate = 0.00;

    /**
     * @var ArrayCollection
     */
    private $wallets;

    /**
     * @var ArrayCollection
     */
    private $replenishments;


    public function __construct()
    {
        $this->wallets = new ArrayCollection();
        $this->replenishments = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Currency
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set abbr
     *
     * @param string $abbr
     * @return Currency
     */
    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;
        return $this;
    }

    /**
     * Get abbr
     *
     * @return string 
     */
    public function getAbbr()
    {
        return $this->abbr;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     * @return Currency
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set alpha3
     *
     * @param string $alpha3
     * @return Currency
     */
    public function setAlpha3($alpha3)
    {
        $this->alpha3 = $alpha3;
        return $this;
    }

    /**
     * Get alpha3
     *
     * @return string 
     */
    public function getAlpha3()
    {
        return $this->alpha3;
    }

    /**
     * Set numericCode
     *
     * @param integer $numericCode
     * @return Currency
     */
    public function setNumericCode($numericCode)
    {
        $this->numericCode = $numericCode;
        return $this;
    }

    /**
     * Get numericCode
     *
     * @return integer 
     */
    public function getNumericCode()
    {
        return $this->numericCode;
    }

    /**
     * Set exchangeRate
     *
     * @param float $exchangeRate
     * @return Currency
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
        return $this;
    }

    /**
     * Get exchangeRate
     *
     * @return float
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * Add wallets
     *
     * @param \BW\UserBundle\Entity\Wallet $wallets
     * @return Currency
     */
    public function addWallet(Wallet $wallets)
    {
        $this->wallets[] = $wallets;
        return $this;
    }

    /**
     * Remove wallets
     *
     * @param \BW\UserBundle\Entity\Wallet $wallets
     */
    public function removeWallet(Wallet $wallets)
    {
        $this->wallets->removeElement($wallets);
    }

    /**
     * Get wallets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWallets()
    {
        return $this->wallets;
    }

    /**
     * Add replenishments
     *
     * @param \BW\UserBundle\Entity\Replenishment $replenishments
     * @return Currency
     */
    public function addReplenishment(Replenishment $replenishments)
    {
        $this->replenishments[] = $replenishments;
        return $this;
    }

    /**
     * Remove replenishments
     *
     * @param \BW\UserBundle\Entity\Replenishment $replenishments
     */
    public function removeReplenishment(Replenishment $replenishments)
    {
        $this->replenishments->removeElement($replenishments);
    }

    /**
     * Get replenishments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReplenishments()
    {
        return $this->replenishments;
    }
}
