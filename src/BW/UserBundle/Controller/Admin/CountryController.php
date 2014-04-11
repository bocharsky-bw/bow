<?php

namespace BW\UserBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\Country;
use BW\UserBundle\Form\CountryType;

class CountryController extends BWController
{
    /**
     * Список всех пользователей
     * 
     * @return render
     */
    public function countriesAction() {
        $data = $this->getPropertyOverload();
        
        $data->countries = $this->getDoctrine()->getRepository('BWUserBundle:Country')->findBy(
            array(),
            array(
                'name' => 'asc',
            )
        );
        
        return $this->render('BWUserBundle:Admin/Country:countries.html.twig', $data->toArray());
    }
    
    public function countryAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $country = $this->getDoctrine()->getRepository('BWUserBundle:Country')->find($id);
        } else {
            $country = new Country();
        }
        
        $form = $this->createForm(new CountryType(), $country);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($country);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                                'danger',
                                'Страна успешно удалена.'
                            );
                        
                        return $this->redirect( $this->generateUrl('admin_countries') );
                    }
                }
                
                $em->persist($country);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Страна успешно сохранена.'
                );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_countries') );
                }
                
                
                return $this->redirect( $this->generateUrl('admin_country_edit', array('id' => $country->getId())) );
            }
        }
        
        $data->country = $country;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWUserBundle:Admin/Country:country-edit.html.twig', $data->toArray());
        }
        
        return $this->render('BWUserBundle:Admin/Country:country-add.html.twig', $data->toArray());
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
