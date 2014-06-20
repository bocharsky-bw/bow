<?php

namespace BW\MainBundle\Utility;

use Symfony\Component\Form\FormInterface;

/**
 * Class FormUtility
 * @package BW\MainBundle\Utility
 */
class FormUtility {

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    public static function addCreateButton(FormInterface $form)
    {
        return $form->add('create', 'submit', array(
            'label' => 'Создать',
            'attr' => array(
                'class' => 'btn btn-primary icon-save before-padding',
                'title' => 'Создать элемент и остаться в режиме редактирования',
            ),
        ));
    }

    public static function addCreateAndCloseButton(FormInterface $form)
    {
        return $form->add('createAndClose', 'submit', array(
            'label' => 'Создать и закрыть',
            'attr' => array(
                'class' => 'btn btn-primary icon-save before-padding',
                'title' => 'Создать элемент и выйти из режима редактирования',
            ),
        ));
    }

    public static function addUpdateButton(FormInterface $form)
    {
        return $form->add('update', 'submit', array(
            'label' => 'Обновить',
            'attr' => array(
                'class' => 'btn btn-primary icon-save before-padding',
                'title' => 'Обновить элемент и остаться в режиме редактирования',
            ),
        ));
    }

    public static function addUpdateAndCloseButton(FormInterface $form)
    {
        return $form->add('updateAndClose', 'submit', array(
            'label' => 'Обновить и закрыть',
            'attr' => array(
                'class' => 'btn btn-primary icon-save before-padding',
                'title' => 'Обновить элемент и выйти из режима редактирования',
            ),
        ));
    }

    public static function addDeleteButton(FormInterface $form)
    {
        return $form->add('delete', 'submit', array(
            'label' => 'Удалить',
            'attr' => array(
                'class' => 'btn btn-default icon-remove before-padding',
                'title' => 'Удалить элемент из БД',
                'onclick' => "return confirm('Удалить элемент из БД?')",
            )
        ));
    }
} 