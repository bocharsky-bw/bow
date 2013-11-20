<?php

namespace BW\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class BWEntity {
    
    public function setDefaultValues(array $values) {
        
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
        
        return $this;
    }
    
}