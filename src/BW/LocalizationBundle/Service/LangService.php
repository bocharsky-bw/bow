<?php

namespace BW\LocalizationBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Description of Lang
 *
 * @author Victor
 */
class LangService {
    
    /**
     * Service Container Object
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;
    
    /**
     * Lang Entity Object
     * @var \BW\LocalizationBundle\Entity\Lang
     */
    private $lang;
    
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        
        $this->lang = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('BWLocalizationBundle:Lang')
                ->findOneBy(array(
                    'sign' => $this->container->get('request')->getLocale(),
                ));
        
        if ( ! $this->lang) {
            throw new NotFoundHttpException("Ошибка 404. Запрашиваемая Вами страница на языке \"{$this->container->get('request')->getLocale()}\" не найдена");
        }
    }
    
    public function __call($method, $arguments) {
        
        if ( method_exists($this->lang, $method) ) {
            //return $this->lang->$method($arguments);
            return call_user_func_array(array($this->lang, $method), $arguments);
        }
        
        $getter = 'get'. ucfirst($method);
        if ( method_exists($this->lang, $getter) ) {
            //return $this->lang->$getter($arguments);
            return call_user_func_array(array($this->lang, $getter), $arguments);
        }
        
        $isser = 'is'. ucfirst($method);
        if ( method_exists($this->lang, $isser) ) {
            //return $this->lang->$isser($arguments);
            return call_user_func_array(array($this->lang, $isser), $arguments);
        }
    }
    
    
    public function getLang() {
        
        return $this->lang;
    }
    
}
