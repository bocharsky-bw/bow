<?php

namespace BW\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BW\UserBundle\Entity\Country
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity(repositoryClass="BW\UserBundle\Entity\CountryRepository")
 */
class Country
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
     * @ORM\Column(name="name", type="string", length=120)
     */
    private $name;

    /**
     * @var string $nameEn
     *
     * @ORM\Column(name="name_en", type="string", length=60)
     */
    private $nameEn;

    /**
     * @var string $alpha2
     *
     * @ORM\Column(name="alpha2", type="string", length=2)
     */
    private $alpha2;

    /**
     * @var string $alpha3
     *
     * @ORM\Column(name="alpha3", type="string", length=3)
     */
    private $alpha3;

    /**
     * @var integer $numericCode
     *
     * @ORM\Column(name="numeric_code", type="smallint")
     */
    private $numericCode;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=15)
     */
    private $code;
    
    /**
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     */
    private $currency;
    
    /**
     * @var string $code
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;
    
    
    public function __construct() {
        $this->enabled = TRUE;
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
     * @return Country
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
     * Set nameEn
     *
     * @param string $nameEn
     * @return Country
     */
    public function setNameEn($nameEn)
    {
        $this->nameEn = $nameEn;
        return $this;
    }

    /**
     * Get nameEn
     *
     * @return string 
     */
    public function getNameEn()
    {
        return $this->nameEn;
    }

    /**
     * Set alpha2
     *
     * @param string $alpha2
     * @return Country
     */
    public function setAlpha2($alpha2)
    {
        $this->alpha2 = $alpha2;
        return $this;
    }

    /**
     * Get alpha2
     *
     * @return string 
     */
    public function getAlpha2()
    {
        return $this->alpha2;
    }

    /**
     * Set alpha3
     *
     * @param string $alpha3
     * @return Country
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
     * @return Country
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
     * Set code
     *
     * @param string $code
     * @return Country
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set currency
     *
     * @param BW\UserBundle\Entity\Currency $currency
     * @return Country
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

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Country
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
}
