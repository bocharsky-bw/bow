<?php

namespace BW\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Entity\CustomField;
use BW\BlogBundle\Form\CustomFieldType;

class CustomFieldController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function customFieldsAction() {
        $data = $this->getPropertyOverload();
        
        $data->customFields = $this->getDoctrine()->getRepository('BWBlogBundle:CustomField')->findAll();
        
        return $this->render('BWBlogBundle:Admin/CustomField:custom-fields.html.twig', $data->toArray());
    }
    
    public function customFieldAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        
        //$request->getSession()->set('AllowCKFinder', TRUE); // Allow to use CKFinder
        
        if ($id) {
            $customFields = $this->getDoctrine()->getRepository('BWBlogBundle:CustomField')->find($id);
        } else {
            $customFields = new CustomField;
        }
        
        $form = $this->createForm(new CustomFieldType(), $customFields);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($customFields);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Настраиваемое поле успешно удалено из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_custom_fields') );
                    }
                }
                
                if ( ! $customFields->getId()) {
                    $em->persist($customFields);
                }
                $em->flush();
                
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Настраиваемое поле успешно сохранено в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_custom_fields') );
                }
                
                return $this->redirect( $this->generateUrl('admin_custom_field_edit', array('id' => $customFields->getId())) );
            }
        }
        
        $data->customFields = $customFields;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/CustomField:edit-custom-field.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/CustomField:add-custom-field.html.twig', $data->toArray());
    }
    
}
