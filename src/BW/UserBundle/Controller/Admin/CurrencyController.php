<?php

namespace BW\UserBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\Currency;
use BW\UserBundle\Form\CurrencyType;

class CurrencyController extends BWController
{
    /**
     * Список всех пользователей
     * 
     * @return render
     */
    public function currenciesAction() {
        $data = $this->getPropertyOverload();
        
        $data->currencies = $this->getDoctrine()->getRepository('BWUserBundle:Currency')->findBy(
            array(),
            array(
                'name' => 'asc',
            )
        );
        
        return $this->render('BWUserBundle:Admin/Currency:currencies.html.twig', $data->toArray());
    }
    
    public function currencyAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $currency = $this->getDoctrine()->getRepository('BWUserBundle:Currency')->find($id);
        } else {
            $currency = new Currency();
        }
        
        $form = $this->createForm(new CurrencyType(), $currency);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($currency);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                                'danger',
                                'Валюта успешно удалена.'
                            );
                        
                        return $this->redirect( $this->generateUrl('admin_currencies') );
                    }
                }
                
                $em->persist($currency);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Валюта успешно сохранена.'
                );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_currencies') );
                }
                
                
                return $this->redirect( $this->generateUrl('admin_currency_edit', array('id' => $currency->getId())) );
            }
        }
        
        $data->currency = $currency;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWUserBundle:Admin/Currency:currency-edit.html.twig', $data->toArray());
        }
        
        return $this->render('BWUserBundle:Admin/Currency:currency-add.html.twig', $data->toArray());
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
