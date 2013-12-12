<?php

namespace BW\LocalizationBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

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
        $this->_init();
    }
    
    
    private function _init() {
        $this->lang = $this->container->get('doctrine.orm.entity_manager')
                ->getRepository('BWLocalizationBundle:Lang')
                ->findOneBy(array(
                    'sign' => $this->container->get('request')->getLocale(),
                ));
        
        if ( ! $this->lang) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Запрашиваемая страница на языке \"{$this->container->get('request')->getLocale()}\" недоступна");
        }
    }

    public function getCurrentLangEntity() {
        
        return $this->lang;
    }
}
