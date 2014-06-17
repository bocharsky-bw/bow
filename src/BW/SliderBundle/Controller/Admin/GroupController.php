<?php

namespace BW\SliderBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use BW\SliderBundle\Entity\Group;
use BW\SliderBundle\Form\GroupType;

class GroupController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Post $post
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function groupsAction() {
        $data = $this->getPropertyOverload();
        
        $data->groups = $this->getDoctrine()->getRepository('BWSliderBundle:Group')->findAll();
        
        return $this->render('BWSliderBundle:Admin/Group:groups.html.twig', $data->toArray());
    }
    
    public function groupAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');

        if ($id) {
            $group = $this->getDoctrine()->getRepository('BWSliderBundle:Group')->find($id);
        } else {
            $group = new Group();
        }
        
        $form = $this->createForm(new GroupType(), $group);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($group);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Группа слайдеров успешно удалена из БД'
                        );

                        return $this->redirect($this->generateUrl('admin_slider_groups'));
                    }
                }
                
                if ( ! $group->getAlias()) {
                    $group->setAlias($group->getName());
                }
                $group->setAlias(
                    strtolower(
                        $this->get('bw_blog.transliter')->toSlug(
                            $group->getAlias()
                    )));
                
                $em->persist($group);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Группа слайдеров успешно сохранена в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_slider_groups') );
                }
                
                return $this->redirect( $this->generateUrl('admin_slider_group_edit', array('id' => $group->getId())) );
            }
        }
        
        $data->group = $group;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWSliderBundle:Admin/Group:edit-group.html.twig', $data->toArray());
        }
        
        return $this->render('BWSliderBundle:Admin/Group:add-group.html.twig', $data->toArray());
    }
    
}
