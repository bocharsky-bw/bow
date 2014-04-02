<?php

namespace BW\BlogBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use BW\BlogBundle\Entity\Post;

class Transliter {

    private $container;
    
    private $alphabet = array(
            'А' =>  'A',
            'Б' =>  'B',
            'В' =>  'V',
            'Г' =>  'G',
            'Д' =>  'D',
            'Е' =>  'E',
            'Ё' =>  'E',
            'Ж' =>  'ZH',
            'З' =>  'Z',
            'И' =>  'I',
            'Й' =>  'Y',
            'К' =>  'K',
            'Л' =>  'L',
            'М' =>  'M',
            'Н' =>  'N',
            'О' =>  'O',
            'П' =>  'P',
            'Р' =>  'R',
            'С' =>  'S',
            'Т' =>  'T',
            'У' =>  'U',
            'Ф' =>  'F',
            'Х' =>  'H',
            'Ц' =>  'TS',
            'Ч' =>  'CH',
            'Ш' =>  'SH',
            'Щ' =>  'SCH',
            'Ъ' =>  '',
            'Ы' =>  'Y',
            'Ь' =>  '',
            'Э' =>  'E',
            'Ю' =>  'YU',
            'Я' =>  'YA',
            'а' =>  'a',
            'б' =>  'b',
            'в' =>  'v',
            'г' =>  'g',
            'д' =>  'd',
            'е' =>  'e',
            'ё' =>  'e',
            'ж' =>  'zh',
            'з' =>  'z',
            'и' =>  'i',
            'й' =>  'y',
            'к' =>  'k',
            'л' =>  'l',
            'м' =>  'm',
            'н' =>  'n',
            'о' =>  'o',
            'п' =>  'p',
            'р' =>  'r',
            'с' =>  's',
            'т' =>  't',
            'у' =>  'u',
            'ф' =>  'f',
            'х' =>  'h',
            'ц' =>  'ts',
            'ч' =>  'ch',
            'ш' =>  'sh',
            'щ' =>  'sch',
            'ъ' =>  '',
            'ы' =>  'y',
            'ь' =>  '',
            'э' =>  'e',
            'ю' =>  'yu',
            'я' =>  'ya',
        );
    

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    
    /**
     * Транслитерация строки
     * 
     * @param string $string Строка для транслитерации
     * @return string Строка транслитом
     **/
    public function translit($string) {
        
        return strtr($string, $this->alphabet);
    }

    /**
     * Транслитерация строки для slug (для использования в адресной строке браузера)
     *  
     * @param string $string Строка для транслитерации
     * @return string Slug транслитом
     **/
    public function toSlug($string) {
        
        return preg_replace('/[^0-9A-Za-z_-]/', '', preg_replace('/\s+|-+/i', '-', $this->translit($string)));
    }
    
    
    public function generateSlug($entity) {
        if ($entity instanceof Post) {
            // Если не задано slug, формирование slug из заголовка
            if ( ! $entity->getSlug()) {
                $entity->setSlug($entity->getHeading());
            }
            // Фильтрация slug и его преобразование в нижний регистр
            $entity->setSlug(strtolower($this->toSlug($entity->getSlug())));
            
            return TRUE;
        }
        
        return FALSE;
    }
    
    
//    public function prePersist(LifecycleEventArgs $args) {
//        $entity = $args->getEntity();
//        //$em = $args->getEntityManager();
//        
//        if ($entity instanceof Post) {
//            // Если не задано slug, формирование slug из заголовка
//            if ( ! $entity->getSlug()) {
//                $entity->setSlug($entity->getHeading());
//            }
//            // Фильтрация slug и его преобразование в нижний регистр
//            $entity->setSlug(strtolower($this->toSlug($entity->getSlug())));
//        }
//    }
//    
//    public function preUpdate(PreUpdateEventArgs $args) {
//        $entity = $args->getEntity();
//        $em = $args->getEntityManager();
//        
//        if ($entity instanceof Post) {
//            if ($args->hasChangedField('slug')) {
//                // Если не задано slug, формирование slug из заголовка
//                if ( ! $entity->getSlug()) {
//                    $args->setNewValue('slug', $entity->getHeading());
//                }
//                // Фильтрация slug и его преобразование в нижний регистр
//                $args->setNewValue('slug', strtolower($this->toSlug($args->getNewValue('slug'))));
//                
//                /* ROUTER */
//                //to delete
////                //$args->setNewValue('route', NULL);
////                $route = $entity->getRoute();
////                $route->setQuery('query');
////                $segments = array();
////                $parent = $entity->getCategory();
////                while ($parent) {
////                    $segments[] = $parent->getSlug();
////                    $parent = $parent->getParent();
////                }
////                $query = ($segments ? implode('/', array_reverse($segments)) .'/' : '') . $entity->getSlug();
////                $route->setPath(($entity->getLang() ? $entity->getLang().'/' : '') . $query);
////                $route->setQuery($query);
////                $route->setLang($entity->getLang());
////                $route->setDefaults(array(
////                    '_controller' => 'BWBlogBundle:Post:post',
////                    'id' => $entity->getId(),
////                ));
////                $em->persist($route);
////                $entity->setRoute(NULL);
////                $entity->setRoute($route);
//////                $em->getUnitOfWork()->addToIdentityMap($route);
////                $route->getQuery('qqqqqqqqqqqqqqqqq');
////                $r = $em->getUnitOfWork()->getOriginalEntityData($route);
////                $r['query'] = 'qqqqqqqqqqqq';
////                $em->getUnitOfWork()->setOriginalEntityData($route, $r);
////                $em->getUnitOfWork()->
////                $em->getUnitOfWork()->computeChangeSets();
////                $r = $em->getUnitOfWork()->getOriginalEntityData($route);
////                var_dump($r);
////                
////                $uow = $em->getUnitOfWork();
////                $meta = $em->getClassMetadata(get_class($route));
////                $uow->recomputeSingleEntityChangeSet($meta, $route);
////                $meta = $em->getClassMetadata(get_class($entity));
////                $uow->recomputeSingleEntityChangeSet($meta, $entity);
//            }
//        }
//    }
}