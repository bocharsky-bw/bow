<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

use BW\UserBundle\Entity\User;
use BW\UserBundle\Form\UserSignUpType;

class UserController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     */
    public function __construct() {
        parent::__construct();
    }
    
    public function signUpAction() {
        if ($this->getUser()) {
            
            return $this->redirect($this->generateUrl('home'));
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        $conn = $this->get('database_connection');

        $user = new User();
        $form = $this->createForm(new UserSignUpType(), $user);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $user->addRole(
                    $em->getRepository('BWUserBundle:Role')->findOneBy(array(
                        'role' => 'ROLE_USER',
                    ))
                );
                $em->persist($user);
                $em->flush();
                
                /* Mailer */
                $message = \Swift_Message::newInstance()
                        ->setSubject('Регистрация на сайте '. $request->server->get('HTTP_HOST'))
                        ->setFrom('test@ndv.net.ua')
                        ->setTo('bocharsky.bw@gmail.com') // for_testing
                        //->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView(
                                'BWUserBundle:User/emails:sign-up.html.twig',
                                array(
                                    'user' => $user,
                                )
                            ),
                            'text/html'
                        )
                    ;
                
                if ($this->get('mailer')->send($message)) {
                    $this->get('session')->getFlashBag()->add('success', 'Поздравляем, Вы успешно зарегистрировались!<br>'
                            .'На указанный Вами e-mail <b>'. $user->getEmail() .'</b> было отпралено сообщения с ссылкой для активации аккаунта.');
                } else {
                    $this->get('session')->getFlashBag()->add('danger', 'Не удалось отправить письмо на указанный Вами e-mail <b>'. $user->getEmail() .'</b>!<br>'
                            .'Если Вы все же уверены что указали правильный e-mail - тогда свяжитесь с администратором сайта.');
                }
                /* /Mailer */
                
                return $this->redirect($this->generateUrl('home'));
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWUserBundle:User:user-sign-up.html.twig', $data->toArray());
    }
    
    public function signInAction() {
        if ($this->getUser()) {
            
            return $this->redirect($this->generateUrl('home'));
        }
        
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return $this->render(
            'BWUserBundle:User:user-sign-in.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
    
    public function signInFormAction(Request $request) {
        $data = $this->getPropertyOverload();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $data->error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $data->error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        $data->last_username = $session->get(SecurityContext::LAST_USERNAME);
        
        $data->request = $request;
        return $this->render('BWUserBundle:User:sign-in-form.html.twig', $data->toArray());
    }
    
    public function emailConfirmAction($hash) {
        $data = $this->getPropertyOverload();
        $em = $this->getDoctrine()->getManager();
        
        $data->user = $em->getRepository('BWUserBundle:User')->findOneBy(
            array(
                'active' => FALSE,
                'confirm' => FALSE,
                'hash' => $hash,
            )
        );
        
        if ($data->user) {
            $data->user->setActive(TRUE);
            $data->user->setConfirm(TRUE);
            $data->user->setHash('');
            $em->flush($data->user);
            
            $this->get('session')->getFlashBag()->add('success', '<b>Ваш аккаунт успешно подтвержден!</b><br>'
                    .'Воспользуйтесь авторизацией чтобы войти в систему');
        } else {
            $this->get('session')->getFlashBag()->add('danger', '<b>Cсылка не действительна!</b><br>'
                    .'Возможно, Вы уже подтвердили свой аккаунт ранее.<br>'
                    .'Попробуйте воспользоваться авторизацией');
        }
        
        return $this->redirect($this->generateUrl('user_sign_in'));
    }
}
