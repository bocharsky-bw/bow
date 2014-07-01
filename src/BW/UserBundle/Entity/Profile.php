<?php

namespace BW\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Profile
 * @package BW\UserBundle\Entity
 */
class Profile
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $surname
     */
    private $surname = '';

    /**
     * @var string $name
     */
    private $name = '';

    /**
     * @var string $patronymic
     */
    private $patronymic = '';

    /**
     * @var string $phone
     */
    private $phone = '';

    /**
     * @var Country $country
     */
    private $country;

    /**
     * @var string $region
     */
    private $region = '';

    /**
     * @var string $city
     */
    private $city = '';

    /**
     * @var string $postcode
     */
    private $postcode = '';

    /**
     * @var string $street
     */
    private $street = '';

    /**
     * @var string $house
     */
    private $house = '';

    /**
     * @var string $apartment
     */
    private $apartment = '';

    /**
     * @var User $user
     */
    private $user;

    /**
     * @var ArrayCollection $wallets
     */
    private $wallets;

    /**
     * @var ArrayCollection $replenishments
     */
    private $replenishments;


    /**
     * The constructor
     */
    public function __construct()
    {
        $this->wallets = new ArrayCollection();
        $this->replenishments= new ArrayCollection();
    }


    /* GETTERS / SETTERS */

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
     * Set surname
     *
     * @param string $surname
     * @return Profile
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Profile
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
     * Set patronymic
     *
     * @param string $patronymic
     * @return Profile
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    /**
     * Get patronymic
     *
     * @return string 
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Profile
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return Profile
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set region
     *
     * @param string $region
     * @return Profile
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Profile
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Profile
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string 
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Profile
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set house
     *
     * @param string $house
     * @return Profile
     */
    public function setHouse($house)
    {
        $this->house = $house;

        return $this;
    }

    /**
     * Get house
     *
     * @return string 
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set apartment
     *
     * @param string $apartment
     * @return Profile
     */
    public function setApartment($apartment)
    {
        $this->apartment = $apartment;

        return $this;
    }

    /**
     * Get apartment
     *
     * @return string 
     */
    public function getApartment()
    {
        return $this->apartment;
    }

    /**
     * Set user
     *
     * @param \BW\UserBundle\Entity\User $user
     *
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BW\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add wallets
     *
     * @param \BW\UserBundle\Entity\Wallet $wallets
     *
     * @return $this
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
     *
     * @return Profile
     */
    public function addReplenishment(Replenishment $replenishments)
    {
        $this->replenishments[] = $replenishments;
        return $this;
    }

    /**
     * Remove replenishments
     *
     * @param \BW\UserBundle\Entity\Replenishment $replenishment
     */
    public function removeReplenishment(Replenishment $replenishment)
    {
        $this->replenishments->removeElement($replenishment);
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
