<?php

namespace BW\MainBundle\Service; 

class PropertyOverload {
    
    /**
     * Dynamic Array - Location for overloaded data
     * @var array
     */
    protected $data = array();


    public function __construct() {
    }
    
    
    public function __set($name, $value) 
    {
        $this->data[$name] = $value;
    }

    public function __get($name) 
    {
        if ( array_key_exists($name, $this->data) ) {
            
            return $this->data[$name];
        }
        
        return null;
    }

    public function __isset($name) 
    {
        return isset( $this->data[$name] );
    }

    public function __unset($name) 
    {
        unset($this->data[$name]);
    }
    
    
    public function toArray() {
        
        return $this->data;
    }
}
