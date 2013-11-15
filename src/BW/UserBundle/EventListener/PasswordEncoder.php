<?php

namespace BW\UserBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use BW\UserBundle\Entity\User;

/**
 * Description of PasswordEncoder
 *
 * @author Victor
 */
class PasswordEncoder {
    
    private $encoderFactory;


    public function __construct(EncoderFactory $encoderFactory) {
        $this->encoderFactory = $encoderFactory;
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        
        if ($entity instanceof User) {
            // Если НЕ установлен конкретный пароль пользователя
            if ( ! $entity->getPassword()) {
                // Генерируем автоматический пароль-заглушку для безопасности
                $entity->setPassword(md5(uniqid(NULL, TRUE)));
            }
            // Хэшируем новый пароль пользователя
            $encoder = $this->encoderFactory->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);
        }
    }
    
    public function preUpdate(PreUpdateEventArgs $args) {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof User) {
            if ($args->hasChangedField('password')) {
                // Если пароль пользователя изменен
                if ($args->getNewValue('password')) {
                    // Хэшируем новый пароль
                    $encoder = $this->encoderFactory->getEncoder($entity);
                    $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
                    $args->setNewValue('password', $password);
                } else {
                    // иначе оставляем старый пароль
                    $args->setNewValue('password', $args->getOldValue('password'));
                }
            }
        }
    }
}