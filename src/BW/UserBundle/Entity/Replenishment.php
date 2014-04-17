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
     * @ORM\Column(name="additive_amount", type="decimal", precision=15, scale=2)
     */
    private $additiveAmount;

    /**
     * @var decimal $equivalentAmount
     *
     * @ORM\Column(name="equivalent_amount", type="decimal", precision=15, scale=2)
     */
    private $equivalentAmount;

    /**
     * @var datetime $created
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean $status 
     * replenishment_statuses в BW/UserBundle/Resources/config/config.yml
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;
    
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
    
    /**
     * @ORM\ManyToOne(targetEntity="Receipt")
     * @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     */
    private $receipt;
    
    
    public function __construct() {
        $this->additiveAmount = 0;
        $this->equivalentAmount = 0;
        $this->status = 0;
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
     * Set status
     *
     * @param smallint $status
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
     * @return smallint 
     */
    public function getStatus()
    {
        return $this->status;
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

    /**
     * Set receipt
     *
     * @param \BW\UserBundle\Entity\Receipt $receipt
     * @return Replenishment
     */
    public function setReceipt(\BW\UserBundle\Entity\Receipt $receipt = null)
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