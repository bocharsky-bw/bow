<?php

namespace BW\MainBundle\Service;

use BW\MainBundle\Service\SluggableInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Class TranslitService
 * @package BW\BlogBundle\Service
 */
class TranslitService
{
    /**
     * @var array The cyrillic/latin character pairs
     */
    private $alphabet = array(
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Ґ' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'YO',
        'Ж' => 'ZH',
        'З' => 'Z',
        'И' => 'I',
        'І' => 'I',
        'Ї' => 'YI',
        'Й' => 'Y',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'KH',
        'Ц' => 'TS',
        'Ч' => 'CH',
        'Ш' => 'SH',
        'Щ' => 'SHCH',
        'Ъ' => '',
        'Ы' => 'Y',
        'Ь' => '',
        'Э' => 'E',
        'Є' => 'E',
        'Ю' => 'YU',
        'Я' => 'YA',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'ґ' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'і' => 'i',
        'ї' => 'yi',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'kh',
        'ц' => 'ts',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'shch',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'є' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
    );
    

    public function __construct()
    {
    }
    
    
    /**
     * Translit the cyrillic to latin string
     * 
     * @param string $string The cyrillic string to translit
     * @return string The translited latin string
     **/
    public function translit($string)
    {
        $string = strtr($string, $this->alphabet);

        return $string;
    }

    /**
     * Translit cyrillic to latin string for use in browser address bar
     *  
     * @param string $string The cyrillic string to translit
     * @return string The translited latin slug
     **/
    public function toSlug($string)
    {
        $slug = $this->translit($string); // translit string
        $slug = preg_replace('/\s+|-+/i', '-', $slug); // replace few hyphens or spaces to one hyphen
        $slug = preg_replace('/[^0-9A-Za-z_-]/', '', $slug); // remove forbidden characters
        $slug = strtolower($slug); // To lowercase

        return $slug;
    }


    public function generateSlugFor(SluggableInterface $entity)
    {
        // If slug is empty, get slug from special slug string (heading, name, etc.)
        if ( ! $entity->getSlug()) {
            $entity->setSlug($entity->getStringForSlug());
        }

        // Filtering slug
        $slug = $entity->getSlug();
        $slug = $this->toSlug($slug);
        $entity->setSlug($slug);

        return $slug;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SluggableInterface) {
            $this->generateSlugFor($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof SluggableInterface) {
            $this->generateSlugFor($entity);
        }
    }
}