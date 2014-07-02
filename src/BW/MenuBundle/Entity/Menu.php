<?php

namespace BW\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class Menu
 * @package BW\MenuBundle\Entity
 */
class Menu
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $alias
     */
    private $alias = '';

    /**
     * @var string $name
     */
    private $name = '';

    /**
     * @var string $description
     * @TODO Rename to "shortDescription"
     */
    private $description = '';

    /**
     * @var ArrayCollection $items
     */
    private $items;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }


    /**
     * Set default values
     * 
     * ORM\PrePersist
     * @param LifecycleEventArgs $args
     * @return Category
     */
    public function setDefaultValues(LifecycleEventArgs $args) {
        $values = array(
            'alias' => '',
            'description' => '',
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
     * Set alias
     *
     * @param string $alias
     * @return Menu
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Menu
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
     * Set description
     *
     * @param string $description
     * @return Menu
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
     * Add items
     *
     * @param \BW\MenuBundle\Entity\Item $items
     * @return Menu
     */
    public function addItem(Item $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \BW\MenuBundle\Entity\Item $items
     */
    public function removeItem(Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
}
