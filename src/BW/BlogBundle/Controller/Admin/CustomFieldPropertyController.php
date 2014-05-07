<?php

namespace BW\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\BlogBundle\Entity\CustomFieldProperty;
use BW\BlogBundle\Form\CustomFieldPropertyType;

class CustomFieldPropertyController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function customFieldPropertyAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        
        //$request->getSession()->set('AllowCKFinder', TRUE); // Allow to use CKFinder
        
        if ($id) {
            $customFieldProperty = $this->getDoctrine()->getRepository('BWBlogBundle:customFieldProperty')->find($id);
        } else {
            $customFieldProperty = new CustomFieldProperty();
        }
        
        $form = $this->createForm(new CustomFieldPropertyType(), customFieldProperty);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($customFieldProperty);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Настраиваемое поле успешно удалено из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_custom_fields') );
                    }
                }
                
                if ( ! $customFieldProperty->getId()) {
                    $em->persist($customFieldProperty);
                }
                $em->flush();
                
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Настраиваемое поле успешно сохранено в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_custom_fields') );
                }
                
                return $this->redirect( $this->generateUrl('admin_custom_field_edit', array('id' => $customFieldProperty->getId())) );
            }
        }
        
        $data->customFieldProperty = $customFieldProperty;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWBlogBundle:Admin/CustomField:edit-custom-field.html.twig', $data->toArray());
        }
        
        return $this->render('BWBlogBundle:Admin/CustomField:add-custom-field.html.twig', $data->toArray());
    }
    
}
