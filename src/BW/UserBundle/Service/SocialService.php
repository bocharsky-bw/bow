<?php

namespace BW\UserBundle\Service;

/**
 * Description of SocialService
 *
 * @author BrainForce 3.0
 */
class SocialService {
    
    /**
     * The Facebook instance
     * 
     * @var \Facebook
     */
    protected $facebook;
    

    public function __construct(array $config) {
        /* Facebook */
        $this->facebookConfig = $config;
        if ($config['is_active']) {
            $this->facebook = new \Facebook(array(
                'appId' => $config['app_id'],
                'secret' => $config['secret'],
            ));
        }
    }
    
    
    /**
     * Get the Facebook instance
     * @return \Facebook
     */
    public function getFacebook() {
        
        return $this->facebook;
    }
    
}
