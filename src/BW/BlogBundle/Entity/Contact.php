<?php

namespace BW\BlogBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use BW\LocalizationBundle\Entity\Lang;
use BW\RouterBundle\Entity\Route;

/**
 * Class Contact
 * @package BW\BlogBundle\Entity
 */
class Contact
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $heading
     */
    private $heading = '';

    /**
     * @var string $country
     */
    private $country = '';

    /**
     * @var string $city
     */
    private $city = '';

    /**
     * @var string $street
     */
    private $street = '';

    /**
     * @var string $house
     */
    private $house = '';

    /**
     * @var string $office
     */
    private $office = '';

    /**
     * @var string $email
     */
    private $email = '';

    /**
     * @var string $phone
     */
    private $phone = '';
    
    /**
     * @var string $skype
     */
    private $skype = '';
    
    /**
     * @var string $person
     */
    private $person = '';
    
    /**
     * @var string $companyName
     */
    private $companyName = '';
    
    /**
     * @var string $companyDescription
     */
    private $companyDescription = '';

    /**
     * @var string $description
     */
    private $description = '';
    
    /**
     * @var boolean $captcha
     */
    private $captcha = false;

    /**
     * @var string $map
     */
    private $map = '';
    
    /**
     * @var string $slug
     */
    private $slug = '';
    
    /**
     * @var string $title
     */
    private $title = '';
    
    /**
     * @var string $metaDescription
     */
    private $metaDescription = '';

    /**
     * @var integer $route
     */
    private $route;

    /**
     * @var integer $lang
     */
    private $lang;


    /**
     * The constructor
     */
    public function __construct()
    {
    }


    /**
     * Set default values
     *
     * ORM\PrePersist
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     * @return $this
     */
    public function setDefaultValues(LifecycleEventArgs $args) {
        $values = array(
            'country' => '',
            'city' => '',
            'street' => '',
            'house' => '',
            'office' => '',
            'person' => '',
            'email' => '',
            'phone' => '',
            'skype' => '',
            'companyName' => '',
            'companyDescription' => '',
            'description' => '',
            'map' => '',
            'slug' => '',
            'title' => '',
            'metaDescription' => '',
        );
        
        $item = $args->getEntity();
        $class = __CLASS__;
        if ($item instanceof $class) {
            foreach ($values as $field => $value) {
                $getter = 'get'. ucfirst($field);
                if (method_exists($this, $getter)) {
                    if ($this->$getter() === NULL) {
                        $setter = 'set'. ucfirst($field);
                        if (method_exists($this, $setter)) {
                            $this->$setter($value);
                        }
                    }
                }
            }
        }
        
        return $this;
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
     * Set heading
     *
     * @param string $heading
     * @return Contact
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * Get heading
     *
     * @return string 
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return Contact
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Contact
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
     * Set street
     *
     * @param string $street
     * @return Contact
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
     * @return Contact
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
     * Set office
     *
     * @param string $office
     * @return Contact
     */
    public function setOffice($office)
    {
        $this->office = $office;

        return $this;
    }

    /**
     * Get office
     *
     * @return string 
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contact
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
     * Set phone
     *
     * @param string $phone
     * @return Contact
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
     * Set skype
     *
     * @param string $skype
     * @return Contact
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set person
     *
     * @param string $person
     * @return Contact
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return string 
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return Contact
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyDescription
     *
     * @param string $companyDescription
     * @return Contact
     */
    public function setCompanyDescription($companyDescription)
    {
        $this->companyDescription = $companyDescription;

        return $this;
    }

    /**
     * Get companyDescription
     *
     * @return string 
     */
    public function getCompanyDescription()
    {
        return $this->companyDescription;
    }

    /**
     * Set map
     *
     * @param string $map
     * @return Contact
     */
    public function setMap($map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return string 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Contact
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Contact
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Contact
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Contact
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set captcha
     *
     * @param boolean $captcha
     * @return Contact
     */
    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;

        return $this;
    }

    /**
     * Get captcha
     *
     * @return boolean
     */
    public function isCaptcha()
    {
        return $this->captcha;
    }

    /**
     * Get captcha
     *
     * @return boolean
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

    /**
     * Set route
     *
     * @param \BW\RouterBundle\Entity\Route $route
     * @return Contact
     */
    public function setRoute(Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \BW\RouterBundle\Entity\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set lang
     *
     * @param \BW\LocalizationBundle\Entity\Lang $lang
     * @return Contact
     */
    public function setLang(Lang $lang = null)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return \BW\LocalizationBundle\Entity\Lang
     */
    public function getLang()
    {
        return $this->lang;
    }
}
