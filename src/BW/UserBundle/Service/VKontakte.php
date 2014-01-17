<?php

namespace BW\UserBundle\Service;

/**
 * Description of VKontakte
 *
 * @author Victor
 */
class VKontakte {
    
    const VERSION = '5.5';

    /**
     * The application ID
     * @var integer
     */
    private $appId;
    
    /**
     * The application secret code
     * @var integer
     */
    private $secret;
    
    private $scope = array();
    
    private $redirect_uri;
    
    private $responceType = 'code';
    
    private $accessToken;
    

    /**
     * The VKontakte instance constructor
     * @param array $config
     */
    public function __construct(array $config) {
        if (isset($config['app_id'])) {
            $this->setAppId($config['app_id']);
        }
        if (isset($config['secret'])) {
            $this->setSecret($config['secret']);
        }
        if (isset($config['scopes'])) {
            $this->setScope($config['scopes']);
        }
        if (isset($config['redirect_uri'])) {
            $this->setRedirectUri($config['redirect_uri']);
        }
        if (isset($config['response_type'])) {
            $this->setResponceType($config['response_type']);
        }
    }
    
    
    public function setAppId($appId) {
        $this->appId = $appId;
        
        return $this;
    }
    
    public function getAppId() {
        
        return $this->appId;
    }
    
    public function getSecret() {
        
        return $this->secret;
    }
    
    public function setSecret($secret) {
        $this->secret = $secret;
        
        return $this;
    }
    
    public function getScope() {
        
        return $this->scope;
    }
    
    public function setScope(array $scope) {
        $this->scope = $scope;
        
        return $this;
    }
    
    public function getRedirectUri() {
        
        return $this->redirect_uri;
    }
    
    public function setRedirectUri($redirect_uri) {
        $this->redirect_uri = $redirect_uri;
        
        return $this;
    }
    
    public function getResponceType() {
        
        return $this->responceType;
    }
    
    public function setResponceType($responceType) {
        $this->responceType = $responceType;
        
        return $this;
    }
    
    /**
     * Make an API call.
     *
     * @return mixed The decoded response
     */
    public function api($string/* polymorphic */) {
//        $args = func_get_args();
//        $method = array_shift($args);
//        $query = ;
//        
//        $url = 'https://api.vk.com/method/'. $method .'?'
//        var_dump($args);
        $url = 'https://api.vk.com/method/' .$string. '&access_token='. $this->accessToken;
        
        return json_decode($this->curl($url));
    }
    
    public function getLoginUrl() {
        
        return 'https://oauth.vk.com/authorize'
                .'?client_id='. urlencode($this->getAppId())
                .'&scope='. urlencode(implode(',', $this->getScope()))
                .'&redirect_uri='. urlencode($this->getRedirectUri())
                .'&response_type='. urlencode($this->getResponceType())
                .'&v='. urlencode(self::VERSION)
            ;
    }
    
    public function setAccessToken($token) {
        $data = json_decode($token);
        $this->accessToken = $data->access_token;
        $this->expiresIn = $data->expires_in;
        $this->userId = $data->user_id;
    }
    
    public function getAccessToken($code = NULL) {
        $code = $code ? $code : $_GET['code'];
        
        $url = 'https://oauth.vk.com/access_token'
                .'?client_id='. urlencode($this->getAppId())
                .'&client_secret='. urlencode($this->getSecret())
                .'&code='. urlencode($code)
                .'&redirect_uri='. urlencode($this->getRedirectUri())
            ;
        
        $token = $this->curl($url);
        $this->setAccessToken($token);

        return $token;
    }
    
    protected function curl($url) {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // disable SSL verifying
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        // $output contains the output string
        $result = curl_exec($ch);
        
        if ( ! $result) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
        }
        
        // close curl resource to free up system resources
        curl_close($ch);
        
        if (isset($errno) && isset($error)) {
            throw new \Exception($error, $errno);
        }
        
        return $result;
    }
}