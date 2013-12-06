<?php

namespace BW\UserBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\User;
use BW\UserBundle\Form\UserType;

class UserController extends BWController
{
    /**
     * Список всех пользователей
     * 
     * @return render
     */
    public function usersAction() {
        $data = $this->getPropertyOverload();
        
        $data->users = $this->getDoctrine()->getRepository('BWUserBundle:User')->findBy(
            array(),
            array()
        );
        
        return $this->render('BWUserBundle:Admin/User:users.html.twig', $data->toArray());
    }
    
    public function userAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $user = $this->getDoctrine()->getRepository('BWUserBundle:User')->find($id);
        } else {
            $user = new User();
        }
        
        $form = $this->createForm(new UserType(), $user);
        if ( ! $id) {
            $form->remove('delete');
            $user->setPassword('userpass');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        if ($this->getUser()->getId() != $id) {
                            $em->remove($user);
                            $em->flush();
                            $this->get('session')->getFlashBag()->add('danger', '<b>Успешно!</b> Пользователь "'. $user->getUsername() .'" успешно удален из БД');
                        } else {
                            $this->get('session')->getFlashBag()->add('danger', '<b>Ошибка!</b> Вы не можете удалить самого себя.');
                        }
                        
                        return $this->redirect( $this->generateUrl('admin_users') );
                    }
                }
                
                if ( $form->get('generatePassword')->isClicked() ) {
                    $newPassword = $user->generatePassword();
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'Для пользователя был сгенерирован новый пароль: <strong>'. $newPassword .'</strong>'
                    );
                }
                
                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Пользователь успешно сохранен в БД'
                );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_users') );
                }
                
                
                return $this->redirect( $this->generateUrl('admin_user_edit', array('id' => $user->getId())) );
            }
        }
        
        $data->user = $user;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWUserBundle:Admin/User:user-edit.html.twig', $data->toArray());
        }
        
        return $this->render('BWUserBundle:Admin/User:user-add.html.twig', $data->toArray());
    }
    
    /**
     * Блокирование пользователя по его id
     * 
     * @param integer $id
     * @return redirect
     */
    public function toogleActiveAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        if ($this->getUser()->getId() != $id) {
            $user = $em->getRepository('BWUserBundle:User')->find($id);
            $user->setActive( ! $user->getActive());
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', '<b>Успешно!</b> Пользователь "'. $user->getUsername() .'" успешно '. ( $user->isEnabled() ? 'разблокирован' : 'заблокирован' ));
        } else {
            $this->get('session')->getFlashBag()->add('danger', '<b>Ошибка!</b> Вы не можете заблокировать самого себя.');
        }
        
        return $this->redirect($this->generateUrl('admin_users'));
    }
}
