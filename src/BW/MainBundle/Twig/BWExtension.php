<?php

namespace BW\MainBundle\Twig;

class BWExtension extends \Twig_Extension
{
    public function getName() {
        
        return 'bw_extension';
    }
    
    public function getFilters() {
        
        return array(
            new \Twig_SimpleFilter('repeat', array($this, 'repeatFilter')),
        );
    }

    public function repeatFilter($input, $multiplier = 2) {

        return str_repeat($input, $multiplier);
    }
}