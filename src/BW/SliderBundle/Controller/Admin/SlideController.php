<?php

namespace BW\SliderBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;
use BW\SliderBundle\Entity\Slider;
use BW\SliderBundle\Form\SliderType;
use BW\SliderBundle\Entity\Slide;
use BW\SliderBundle\Form\SlideType;

class SlideController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \BW\BlogBundle\Entity\Post $post
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    public function slideAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $slide = $this->getDoctrine()->getRepository('BWSliderBundle:Slide')->find($id);
        } else {
            $slide = new Slide;
        }
        
        $form = $this->createForm(new SlideType(), $slide);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($slide);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Слайдер  успешно удален из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_slider_edit', array('id' => $slide->getSlider()->getId())) );
                    }
                }
                
                $em->persist($slide);
                $em->flush();
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Слайдер успешно сохранен в БД'
                    );
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_slider_edit', array('id' => $slide->getSlider()->getId())) );
                }
                
                return $this->redirect( $this->generateUrl('admin_slide_edit', array('id' => $slide->getId())) );
            }
        }
        
        $data->slide = $slide;
        $data->form = $form->createView();
        
        if ($id) {
            return $this->render('BWSliderBundle:Admin/Slide:edit-slide.html.twig', $data->toArray());
        }
        
        return $this->render('BWSliderBundle:Admin/Slide:add-slide.html.twig', $data->toArray());
    }
    
}
