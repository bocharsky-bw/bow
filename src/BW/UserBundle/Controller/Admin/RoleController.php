<?php

namespace BW\UserBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\User;
use BW\UserBundle\Form\UserType;
use BW\UserBundle\Entity\Role;
use BW\UserBundle\Form\RoleType;

class RoleController extends BWController
{
    /**
     * Список всех групп пользователей
     * 
     * @return render
     */
    public function rolesAction() {
        $data = $this->getPropertyOverload();
        
        $data->roles = $this->getDoctrine()->getRepository('BWUserBundle:Role')->findBy(
            array(),
            array()
        );
        
        return $this->render('BWUserBundle:Admin/Role:roles.html.twig', $data->toArray());
    }
    
    /**
     * Изменение / создание новой роли пользователя
     * 
     * @param integer $id
     * @return render
     */
    public function roleAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $role = $this->getDoctrine()->getRepository('BWUserBundle:Role')->find($id);
        } else {
            $role = new Role();
        }
        
        $form = $this->createForm(new RoleType(), $role);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($role);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Роль успешно удалена из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_roles') );
                    }
                }
                
                $em->persist($role);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Роль успешно сохранена в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_roles') );
                }
                
                return $this->redirect( $this->generateUrl('admin_role_edit', array('id' => $role->getId())) );
            }
        }
        
        $data->role = $role;
        $data->form = $form->createView();
        if ($id) {
            return $this->render('BWUserBundle:Admin/Role:role-edit.html.twig', $data->toArray());
        }
        
        return $this->render('BWUserBundle:Admin/Role:role-add.html.twig', $data->toArray());
    }
}
