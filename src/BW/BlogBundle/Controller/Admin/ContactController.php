<?php

namespace BW\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Entity\Contact;
use BW\BlogBundle\Form\ContactType;

class ContactController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Post $post
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function contactsAction() {
        $data = $this->getPropertyOverload();
        
        $data->contacts = $this->getDoctrine()->getRepository('BWBlogBundle:Contact')->findAll();
        
        return $this->render('BWBlogBundle:Admin/Contact:contacts.html.twig', $data->toArray());
    }
    
    public function contactAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        //$request->getSession()->set('AllowCKFinder', TRUE); // Allow to use CKFinder
        
        if ($id) {
            $contact = $this->getDoctrine()->getRepository('BWBlogBundle:Contact')->find($id);
        } else {
            $contact = new Contact;
        }
        
        $form = $this->createForm(new ContactType(), $contact);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($contact);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Контакт успешно удален из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_contacts') );
                    }
                }
                
                if ( ! $contact->getId()) {
                    $em->persist($contact);
                    $em->flush(); // сохраняем в БД чтобы получить ID элемента для роута
                }
                $route = $contact->getRoute();
                if ( ! $route) {
                    $route = new \BW\RouterBundle\Entity\Route();
                    $contact->setRoute($route);
                    $em->persist($route);
                    $em->flush();
                }
                
                $this->get('bw_blog.transliter')->generateSlug($contact);
                
                $query = $contact->getSlug();
                $route->setPath(($contact->getLang() ? $contact->getLang() .'/' : '') . $query);
                $route->setQuery($query);
                $route->setLang($contact->getLang());
                $route->setDefaults(array(
                    '_controller' => 'BWBlogBundle:Contact:contact',
                    'id' => $contact->getId(),
                ));
                
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Контакт успешно сохранен в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_contacts') );
                }
                
                return $this->redirect( $this->generateUrl('admin_contact_edit', array('id' => $contact->getId())) );
            }
        }
        
        $data->contact = $contact;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/Contact:edit-contact.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/Contact:add-contact.html.twig', $data->toArray());
    }
    
}
