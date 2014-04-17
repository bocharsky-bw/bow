<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\MainBundle\Controller\BWController;

class ContactController extends BWController
{

    public function contactAction($id) {
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();

        $data->contact = $this->getDoctrine()->getRepository('BWBlogBundle:Contact')->find($id);
        if ( ! $data->contact) {
            throw $this->createNotFoundException("Ошибка 404. Запрашиваемая страница не найдена.\nСкорее всего нужно перегенерировать ссылку страницы.");
        }
        
        $form = $this->createFormBuilder(array(
            // default values:
            'fio' => '',
            'email' => '',
            'phone' => '',
            'message' => '',
        ))
                ->add('fio', 'text')
                ->add('email', 'email')
                ->add('phone', 'text')
                ->add('message', 'textarea')
                ->add('send', 'submit')
                ->getForm()
            ;
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $feedback = $form->getData();

                /* Swift Mailer */
                $message = \Swift_Message::newInstance()
                        ->setSubject("Сообщение c сайта {$request->getHttpHost()}")
                        ->setFrom($feedback['email'])
                        ->setTo($data->contact->getEmail())
                        ->setBody(
                                $this->renderView('BWBlogBundle:Contact:feedback.html.twig', array(
                                    'feedback' => $feedback,
                                ))
                            )
                    ;
                if ($this->get('mailer')->send($message)) {
                    $request->getSession()
                            ->getFlashBag()
                            ->add('success', 'Ваше сообщение успешно отправлено')
                        ;
                    
                    return $this->redirect(
                            $this->generateUrl('bw_router_index', array(
                                'q' => $data->contact->getRoute()->getQuery(),
                            ))
                        );
                } else {
                    $request->getSession()
                            ->getFlashBag()
                            ->add('danger', 'Произошла ошибка при отправке сообщения')
                        ;
                }
                /* /Swift Mailer */
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWBlogBundle:Contact:contact.html.twig', $data->toArray());
    }
    
}
