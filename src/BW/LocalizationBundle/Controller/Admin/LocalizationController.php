<?php

namespace BW\LocalizationBundle\Controller\Admin;

use Symfony\Component\Yaml\Yaml;
use BW\MainBundle\Controller\BWController;
use BW\LocalizationBundle\Entity\Lang;
use BW\LocalizationBundle\Form\LangType;

class LocalizationController extends BWController
{

    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct() {
        parent::__construct();
    }

    
    public function indexAction() {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        return $this->render('BWLocalizationBundle:Admin/Localization:index.html.twig', $data->toArray());
    }

    public function systemAction() {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ( ! $request->getLocale()) {
            throw $this->createNotFoundException('Не выбран язык или не включен компонент локализации.');
        }
        
        $path = __DIR__ ."/../../Resources/translations/messages.{$request->getLocale()}.yml";
        
        // Если файла с текущей локализацией не существует - создаем пустой файл
        if ( ! file_exists($path)) {
            file_put_contents($path, '');
        }
        $formData['messages'] = file_get_contents($path);
        
        $form = $this->createFormBuilder($formData)
                ->add('messages', 'hidden')
                // Buttons
                ->add('save', 'submit')
                ->add('saveAndExit', 'submit')
                ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formData = $form->getData();
                
                try {
                    Yaml::parse($formData['messages']);
                    
                    if ( ! file_put_contents($path, $formData['messages'])) {
                        throw new \Exception('Не удалось записать данные в файл!');
                    }
                    
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'Данные успешно сохранены в файл'
                    );

                    if ( $form->get('saveAndExit')->isClicked() ) {

                        return $this->redirect( $this->generateUrl('admin') );
                    }

                    return $this->redirect($this->generateUrl('admin_system_localization'));
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add(
                        'danger',
                        $e->getMessage()
                    );
                }
            }
        }
        
        $data->messages = $formData['messages'];
        $data->form = $form->createView();
        return $this->render('BWLocalizationBundle:Admin/Localization:system-localization.html.twig', $data->toArray());
    }
}
