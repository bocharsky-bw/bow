<?php

namespace BW\LocalizationBundle\Controller\Admin;

use Symfony\Component\Yaml\Yaml;
use BW\MainBundle\Controller\BWController;
use BW\LocalizationBundle\Entity\Lang;
use BW\LocalizationBundle\Form\LangType;

class LangController extends BWController
{

    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct() {
        parent::__construct();
    }


    public function langsMenuItemAction(\Symfony\Component\HttpFoundation\Request $request) {
        $data = $this->getPropertyOverload();

        $data->request = $request;
        $data->langs = $this->getDoctrine()->getRepository('BWLocalizationBundle:Lang')->findAll();
        
        return $this->render('BWLocalizationBundle:Admin/Lang:langs-menu-item.html.twig', $data->toArray());
    }
    
    public function langsAction() {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');

        $data->langs = $this->getDoctrine()->getRepository('BWLocalizationBundle:Lang')->findAll();
        $data->defaultLang = $this->get('service_container')->getParameter('locale');
        
        return $this->render('BWLocalizationBundle:Admin/Lang:langs.html.twig', $data->toArray());
    }

    public function langAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        
        if ($id) { 
            $lang = $em->getRepository('BWLocalizationBundle:Lang')->find($id);
        } else {
            $lang = new Lang();
        }
        
        $form = $this->createForm(new LangType(), $lang);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($id) {
                    if ($form->get('delete')->isClicked()) {
                        $defaultLang = $this->get('service_container')->getParameter('locale'); // язык по умолчанию
                        if ($lang->getSign() == $defaultLang) {
                            $this->get('session')->getFlashBag()->add(
                                'danger',
                                'Нельзя удалить язык по умолчанию'
                            );
                        } else {
                            $em->remove($lang);
                            $em->flush();
                        }

                        return $this->redirect($this->generateUrl('admin_localization_langs', array('_locale' => $defaultLang)));
                    }
                }
                
                $em->persist($lang);
                $em->flush();
                
                if ($form->get('saveAndExit')->isClicked()) {
                    return $this->redirect($this->generateUrl('admin_localization_langs'));
                }
                
                return $this->redirect($this->generateUrl('admin_localization_edit_lang', array('id' => $lang->getId())));
            }
        }
        
        $data->form = $form->createView();
        if ($id) {
            return $this->render('BWLocalizationBundle:Admin/Lang:lang-edit.html.twig', $data->toArray());
        }
        
        return $this->render('BWLocalizationBundle:Admin/Lang:lang-add.html.twig', $data->toArray());
    }
    
}
