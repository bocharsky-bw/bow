<?php

namespace BW\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BW\UserBundle\Entity\Currency
 *
 * @ORM\Table(name="currencies")
 * @ORM\Entity(repositoryClass="BW\UserBundle\Entity\CurrencyRepository")
 */
class Currency
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string $abbr
     *
     * @ORM\Column(name="abbr", type="string", length=20)
     */
    private $abbr;

    /**
     * @var string $symbol
     *
     * @ORM\Column(name="symbol", type="string", length=1)
     */
    private $symbol;

    /**
     * @var string $alpha3
     *
     * @ORM\Column(name="alpha3", type="string", length=3, unique=true)
     */
    private $alpha3;
    
    /**
     * @var string $numericCode
     *
     * @ORM\Column(name="numeric_code", type="smallint", unique=true)
     */
    private $numericCode;

    /**
     * @var decimal $exchangeRate
     *
     * @ORM\Column(name="exchange_rate", type="decimal", precision=15, scale=2)
     */
    private $exchangeRate;
    
    /**
     * @ORM\OneToMany(targetEntity="Wallet", mappedBy="currency")
     */
    private $wallets;
    
    /**
     * @ORM\OneToMany(targetEntity="Replenishment", mappedBy="currency")
     */
    private $replenishments;


    public function __construct() {
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
     * @param decimal $exchangeRate
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
     * @return decimal 
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * Add wallets
     *
     * @param BW\UserBundle\Entity\Wallet $wallets
     * @return Currency
     */
    public function addWallet(\BW\UserBundle\Entity\Wallet $wallets)
    {
        $this->wallets[] = $wallets;
        return $this;
    }

    /**
     * Remove wallets
     *
     * @param BW\UserBundle\Entity\Wallet $wallets
     */
    public function removeWallet(\BW\UserBundle\Entity\Wallet $wallets)
    {
        $this->wallets->removeElement($wallets);
    }

    /**
     * Get wallets
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getWallets()
    {
        return $this->wallets;
    }

    /**
     * Add replenishments
     *
     * @param BW\UserBundle\Entity\Replenishment $replenishments
     * @return Currency
     */
    public function addReplenishment(\BW\UserBundle\Entity\Replenishment $replenishments)
    {
        $this->replenishments[] = $replenishments;
        return $this;
    }

    /**
     * Remove replenishments
     *
     * @param BW\UserBundle\Entity\Replenishment $replenishments
     */
    public function removeReplenishment(\BW\UserBundle\Entity\Replenishment $replenishments)
    {
        $this->replenishments->removeElement($replenishments);
    }

    /**
     * Get replenishments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getReplenishments()
    {
        return $this->replenishments;
    }
}
