<?php

namespace BW\SliderBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\SliderBundle\Entity\Slider;
use BW\SliderBundle\Form\SliderType;
use BW\SliderBundle\Entity\Slide;
use BW\SliderBundle\Form\SlideType;

class SliderController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Post $post
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function slidersAction() {
        $data = $this->getPropertyOverload();
        
        $data->sliders = $this->getDoctrine()->getRepository('BWSliderBundle:Slider')->findAll();
        
        return $this->render('BWSliderBundle:Admin/Slider:sliders.html.twig', $data->toArray());
    }
    
    public function sliderAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        
        if ($id) {
            $slider = $this->getDoctrine()->getRepository('BWSliderBundle:Slider')->find($id);
        } else {
            $slider = new Slider;
        }
        
        $form = $this->createForm(new SliderType(), $slider);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($slider);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Слайдер  успешно удален из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_slider_group_edit', array('id' => $slider->getGroup()->getId())) );
                    }
                }
                
                if ( ! $slider->getAlias()) {
                    $slider->setAlias($slider->getName());
                }
                $slider->setAlias(
                    strtolower(
                        $this->get('bw_blog.transliter')->toSlug(
                            $slider->getAlias()
                    )));
                
                $em->persist($slider);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Слайдер успешно сохранен в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_slider_group_edit', array('id' => $slider->getGroup()->getId())) );
                }
                
                return $this->redirect( $this->generateUrl('admin_slider_edit', array('id' => $slider->getId())) );
            }
        }
        
        $data->slider = $slider;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWSliderBundle:Admin/Slider:edit-slider.html.twig', $data->toArray());
        }
        
        return $this->render('BWSliderBundle:Admin/Slider:add-slider.html.twig', $data->toArray());
    }
    
}
