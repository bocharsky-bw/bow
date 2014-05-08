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
//        $data->customFieldProperties = $this->getDoctrine()->getRepository('BWBlogBundle:CustomFieldProperty')->findAll();
        
        return $this->render('BWBlogBundle:Admin/CustomField:custom-fields.html.twig', $data->toArray());
    }
    
    public function customFieldAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        
        //$request->getSession()->set('AllowCKFinder', TRUE); // Allow to use CKFinder
        
        if ($id) {
            $customField = $this->getDoctrine()->getRepository('BWBlogBundle:CustomField')->find($id);
        } else {
            $customField = new CustomField;
        }
        
        $form = $this->createForm(new CustomFieldType(), $customField);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($customField);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Настраиваемое поле успешно удалено из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_custom_fields') );
                    }
                }
                
                if ( ! $customField->getId()) {
                    $em->persist($customField);
                }
                $em->flush();
                
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Настраиваемое поле успешно сохранено в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_custom_fields') );
                }
                
                return $this->redirect( $this->generateUrl('admin_custom_field_edit', array('id' => $customField->getId())) );
            }
        }
        
        $data->customField = $customField;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/CustomField:edit-custom-field.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/CustomField:add-custom-field.html.twig', $data->toArray());
    }
    
}
