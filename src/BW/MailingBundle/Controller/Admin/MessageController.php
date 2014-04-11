<?php

namespace BW\MailingBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use Symfony\Component\HttpFoundation\Response;
use BW\MailingBundle\Entity\Message;
use BW\MailingBundle\Form\MessageType;
use BW\MailingBundle\Entity\Mailing;

class MessageController extends BWController
{
    
    public function messagesAction() {
        $data = $this->getPropertyOverload();
        
        $data->messages = $this->getDoctrine()
                ->getRepository('BWMailingBundle:Message')
                ->findAll()
            ;
        
        return $this->render('BWMailingBundle:Admin/Message:messages.html.twig', $data->toArray());
    }
    
    public function messageAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $message = $this->getDoctrine()->getRepository('BWMailingBundle:Message')->find($id);
        } else {
            $message = new Message();
        }
        
        $form = $this->createForm(new MessageType(), $message);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($message);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add(
                                'danger',
                                'Сообщение успешно удалено'
                            );
                        
                        return $this->redirect( $this->generateUrl('admin_mailing_messages') );
                    }
                }
                
                $em->persist($message);
                $em->flush();
                
                if ($id) {
                    $this->get('session')->getFlashBag()->add(
                            'success',
                            'Сообщение успешно сохранено.'
                        );
                } else {
                    $this->get('session')->getFlashBag()->add(
                            'success',
                            'Новое сообщение успешно добавлено.'
                        );
                }
                
                
                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_mailing_messages') );
                }
                
                return $this->redirect( $this->generateUrl('admin_mailing_message_edit', array('id' => $message->getId())) );
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWMailingBundle:Admin/Message:add-message.html.twig', $data->toArray());
    }
    
    public function startAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $message = $em
                ->getRepository('BWMailingBundle:Message')
                ->find($id)
            ;

        $message->setSending(TRUE);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add(
                'success',
                'Рассылка сообщений успешно запущена'
            );
        
        return $this->redirect($this->generateUrl('admin_mailing_messages'));
    }
    
    public function stopAction($id) {
        $em = $this->getDoctrine()->getManager();
        
        $message = $em
                ->getRepository('BWMailingBundle:Message')
                ->find($id)
            ;

        $message->setSending(FALSE);
        $em->flush();
        
        $this->get('session')->getFlashBag()->add(
                'warning',
                'Рассылка сообщений успешно остановлена'
            );
        
        return $this->redirect($this->generateUrl('admin_mailing_messages'));
    }
    
    public function sendingAction() {
        $limit = 5; // Количество отправляемых писем за один раз
        $em = $this->getDoctrine()->getManager();
        
        $messages = $em->getRepository('BWMailingBundle:Message')->findBy(array(
            'sending' => TRUE,
        ));
        $i = 0; // Счетчик успешно отправленных сообщений
        foreach ($messages as $message) {
            $users = $em->getRepository('BWUserBundle:User')->findUsersForMailing($message->getId(), 5);
            foreach ($users as $user) {
                $status = FALSE; // Статус успешной отправки сообщения
                
                if ($user->getEmail()) {
                    try {
                        $swiftMessage = \Swift_Message::newInstance()
                                ->setSubject($message->getSubject())
                                ->setFrom($this->get('service_container')->getParameter('mailer_user'))
                                ->setTo($user->getEmail())
                                ->setBody($message->getText())
                                ->setContentType('text/html')
                            ;
                        $status = $this->get('mailer')->send($swiftMessage);
                        
                        if ($status) {
                            $i++;
                            if ($i >= $limit) {
                                break 2; // Выходим из двух циклов
                            }
                        }
                    } catch (\Swift_RfcComplianceException $e) {
                        ; // В случае если e-mail пользователя не валидный или произошла какая то ошибка при отправке
                    }
                }
                
                $mailing = new Mailing;
                $mailing->setMessage($message);
                $mailing->setUser($user);
                $mailing->setSuccess($status);
                $em->persist($mailing);
                $em->flush();
            }
        }
        
        //return $this->redirect($this->generateUrl('admin_mailing_messages'));
        return new Response("{$i} messages sended");
    }
}
