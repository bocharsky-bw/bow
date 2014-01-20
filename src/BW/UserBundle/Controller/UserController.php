<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use BW\UserBundle\Entity\User;
use BW\UserBundle\Form\UserSignUpType;
use BW\UserBundle\Form\UserPasswordResetType;
use BW\UserBundle\Form\UserPasswordNewType;

class UserController extends BWController
{
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Symfony\Component\HttpFoundation\Session\Session $session
     * @global \Doctrine\DBAL\Connection $conn
     * @global \Facebook $facebook
     * @global \Google_Client $client
     * @global \Google_Service_Oauth2_Userinfo $userInfo
     * @global \Exception $e
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Форма для регистрации нового пользователя
     * 
     * @return Response
     */
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
                        ->setTo($user->getEmail())
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
    
    /**
     * Форма для авторизации пользователя
     * 
     * @global \Symfony\Component\Security\Core\SecurityContext $securityContext
     * @global \Symfony\Component\Security\Core\Authentication\Token\AnonymousToken $token
     * @return Response
     */
    public function signInAction() {
        if ($this->getUser()) {
            return $this->redirect($this->generateUrl('home'));
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
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
        return $this->render(
            'BWUserBundle:User:user-sign-in.html.twig',
            $data->toArray()
        );
    }
    
    public function signInWithVkontakteAction() {
        $request = $this->get('request');
        $session = $this->get('session');

        // VKontakte Login init
        $vk = new \BW\UserBundle\Service\VKontakte($this->get('service_container')->getParameter('social')['vkontakte']);
        $vk->setRedirectUri($this->get('router')->generate('user_vkontakte_sign_in', array(), TRUE));
        
        
        if ($request->query->has('code')) {
            $vk->authenticate($request->query->get('code'));
            $session->set('vkontakte_access_token', $vk->getAccessToken());
            
//            if ($session->has('vkontakte_access_token')) {
//                $vk->setAccessToken($session->get('vkontakte_access_token'));
//            }

            if ($vk->isAccessTokenExpired()) {
                $session->remove('vkontakte_access_token');
            } else {
                $userProfile = $vk->api('users.get', array(
                    'fields' => array(
                        'domain',
                    ),
                ))[0];

                if ($userProfile->uid) {
                    try {
                        $user = $this->getDoctrine()
                                ->getRepository('BWUserBundle:User')
                                ->loadUserBySocialId('vkontakteId', $userProfile->uid);
                    } catch (\Exception $e) {
                        // Создание нового пользователя
                        $em = $this->getDoctrine()->getManager();
                        $role = $em->getRepository('BWUserBundle:Role')->findOneBy(array(
                            'role' => 'ROLE_USER',
                        ));
                        $user = new User();
                        $user
                                ->setVkontakteId($userProfile->uid)
                                ->setEmail(NULL)
                                ->setUsername($userProfile->domain)
                                ->setActive(TRUE)
                                ->setConfirm(TRUE)
                                ->setHash('')
                                ->addRole($role)
                                ->generatePassword()
                            ;
                        $em->persist($user);
                        $em->flush();
                    }
                    if ($user) {

                        $this->authorizeUser($user); // return TRUE if success
                    }
                }
            }
        } else {
            // Авторизируем пользователя
            return $this->redirect($vk->getLoginUrl());
        }
        
        return $this->redirect($this->generateUrl('home'));
        //return new \Symfony\Component\HttpFoundation\Response('<a href="'. $vk->getLoginUrl() .'">Auth</a>');
    }
    
    public function signInWithFacebookAction() {
        $request = $this->get('request');
        $facebook = $this->get('bw.user.social')->getFacebook();
        
        if ($request->query->has('code')) {
            if ($facebook->getUser()) {
                $userProfile = $facebook->api('/me/');
                if ($userProfile) {
                    try {
                        $user = $this->getDoctrine()
                                ->getRepository('BWUserBundle:User')
                                ->loadUserBySocialId('facebookId', $userProfile['id']);
                    } catch (\Exception $e) {
                        // Создание нового пользователя
                        $em = $this->getDoctrine()->getManager();
                        $role = $em->getRepository('BWUserBundle:Role')->findOneBy(array(
                            'role' => 'ROLE_USER',
                        ));
                        $user = new User();
                        $user
                                ->setFacebookId($userProfile['id'])
                                ->setEmail($userProfile['email'])
                                ->setUsername($userProfile['username'])
                                ->setActive(TRUE)
                                ->setConfirm(TRUE)
                                ->setHash('')
                                ->addRole($role)
                                ->generatePassword()
                            ;
                        $em->persist($user);
                        $em->flush();
                    }
                    if ($user) {
                        
                        $this->authorizeUser($user); // return TRUE if success
                    }
                }
            }
        } else {
            $params = array(
                'scope' => $this->get('service_container')->getParameter('social')['facebook']['scopes'],
                'redirect_uri' => $this->get('router')->generate('user_facebook_sign_in', array(), TRUE),
            );
            
            return $this->redirect($facebook->getLoginUrl($params));
        }
        
        return $this->redirect($this->generateUrl('home'));
    }
    
    public function signInWithGoogleAction() {
        /**
         * When we create the service here, we pass the
         * client to it. The client then queries the service
         * for the required scopes, and uses that when
         * generating the authentication URL later.
         */
        $request = $this->get('request');
        $session = $this->get('session');
        $client = $this->get('bw.user.social')->getGoogleClient();
        $service = new \Google_Service_Oauth2($client);
        

        /**
         * If we're logging out we just need to clear our
         * local access token in this case
         */
//        if ($request->query->has('logout')) {
//            $session->remove('google_access_token');
//        }

        /**
         * If we have a code back from the OAuth 2.0 flow,
         * we need to exchange that with the authenticate()
         * function. We store the resultant access token
         * bundle in the session, and redirect to ourself.
         */
        if ($request->query->has('code')) {
            $client->authenticate($request->query->get('code'));
            $session->set('google_access_token', $client->getAccessToken());

            /**
             * If we have an access token, we can make
             * requests, else we generate an authentication URL.
             */
            if ($session->has('google_access_token')) {
                $client->setAccessToken($session->get('google_access_token'));
            }

            /**
             * If we're signed in and have a request to shorten
             * a URL, then we create a new URL object, set the
             * unshortened URL, and call the 'insert' method on
             * the 'url' resource. Note that we re-store the
             * access_token bundle, just in case anything
             * changed during the request - the main thing that
             * might happen here is the access token itself is
             * refreshed if the application has offline access.
             */
            if ($client->isAccessTokenExpired()) {
                $session->remove('google_access_token');
            } else {
                $userInfo = $service->userinfo->get();
                if ($userInfo->id) {
                    try {
                        $user = $this->getDoctrine()
                                ->getRepository('BWUserBundle:User')
                                ->loadUserBySocialId('googleId', $userInfo->id);
                    } catch (\Exception $e) {
                        // Создание нового пользователя
                        $em = $this->getDoctrine()->getManager();
                        $role = $em->getRepository('BWUserBundle:Role')->findOneBy(array(
                            'role' => 'ROLE_USER',
                        ));
                        $user = new User();
                        $user
                                ->setGoogleId($userInfo->id)
                                ->setEmail($userInfo->email)
                                ->setUsername($userInfo->id)
                                ->setActive(TRUE)
                                ->setConfirm(TRUE)
                                ->setHash('')
                                ->addRole($role)
                                ->generatePassword()
                            ;
                        $em->persist($user);
                        $em->flush();
                    }
                    if ($user) {
                        
                        $this->authorizeUser($user); // return TRUE if success
                    }
                }
            }
        } else {
            
            return $this->redirect($client->createAuthUrl());
        }
        
        return $this->redirect($this->generateUrl('home'));
    }
    
    public function signOutAction() {
        // Facebook logout
        $this->signOutFacebook();
        
        return $this->redirect($this->generateUrl('home'));
    }
    
    private function signOutFacebook() {
        $facebook = $this->get('bw.user.social')->getFacebook();
        
        return $facebook->destroySession();
    }
    
    /**
     * Блок с формой для авторизации пользователя
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
//    public function signInFormAction(Request $request) {
//        $data = $this->getPropertyOverload();
//        $session = $request->getSession();
//
//        // get the login error if there is one
//        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
//            $data->error = $request->attributes->get(
//                SecurityContext::AUTHENTICATION_ERROR
//            );
//        } else {
//            $data->error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
//            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
//        }
//        $data->last_username = $session->get(SecurityContext::LAST_USERNAME);
//        
//        $data->request = $request;
//        return $this->render('BWUserBundle:User:sign-in-form.html.twig', $data->toArray());
//    }
    
    /**
     * Подтверждение e-mail и активация аккаунта
     * 
     * @param type $hash
     * @return redirect
     */
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
    
    /**
     * Сброс старого пароля пользователя
     * 
     * @return Response
     */
    public function passwordResetAction() {
        if ($this->getUser()) {
            
            return $this->redirect($this->generateUrl('home'));
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        $u = array(
            'email' => '',
        );
        $form = $this->createForm(new UserPasswordResetType(), $u);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $u = $form->getData();
                $em = $this->getDoctrine()->getManager();
                
                $user = $em->getRepository('BWUserBundle:User')->findOneBy(
                    array(
                        'email' => $u['email'],
                    )
                );
                
                if ($user) {
                    $user->generateHash();
                    $em->flush();
                    
                    /* Mailer */
                    $message = \Swift_Message::newInstance()
                            ->setSubject('Сброс пароля на сайте '. $request->server->get('HTTP_HOST'))
                            ->setFrom('test@ndv.net.ua')
                            ->setTo($user->getEmail())
                            ->setBody(
                                $this->renderView(
                                    'BWUserBundle:User/emails:password-reset.html.twig',
                                    array(
                                        'user' => $user,
                                    )
                                ),
                                'text/html'
                            )
                        ;

                    if ($this->get('mailer')->send($message)) {
                        $this->get('session')->getFlashBag()->add('success', "На указанный Вами e-mail <b>{$u['email']}</b> было отправлено сообщение с инструкциями по сбросу пароля.<br>"
                            .'Если Вы все-таки вспомнили свой пароль - проигнорируйте письмо и воспользуйтесь авторизацией.');
                    } else {
                        $this->get('session')->getFlashBag()->add('danger', 'Не удалось отправить письмо на указанный Вами e-mail <b>'. $user->getEmail() .'</b>!<br>'
                                .'Если Вы все же уверены что указали правильный e-mail - тогда свяжитесь с администратором сайта.');
                    }
                    /* /Mailer */
                    
                    return $this->redirect($this->generateUrl('user_sign_in'));
                } else {
                    $this->get('session')->getFlashBag()->add('danger', "Пользователь с e-mail <b>{$u['email']}</b> не найден.<br>"
                            ."Возможно, Вы еще не зарегистрированы или указали неправильный e-mail адрес");
                    
                    return $this->redirect($this->generateUrl('user_password_reset'));
                }
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWUserBundle:User:reset-password.html.twig', $data->toArray());
    }
    
    /**
     * Сохранение нового пароля пользователя
     * 
     * @param string $hash
     * @return type
     * @throws type
     */
    public function passwordNewAction($hash) {
        if ($this->getUser()) {
            
            return $this->redirect($this->generateUrl('home'));
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        
        $data->user = $em->getRepository('BWUserBundle:User')->findOneBy(
            array(
                'hash' => $hash,
            )
        );
        
        if ( ! $data->user) {
            throw $this->createNotFoundException('Cсылка не действительна! '
                    .'Повторите выполненные ранее действия или свяжитесь с администратором сайта.');
        }
        
        $form = $this->createForm(new UserPasswordNewType(), $data->user);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data->user->setHash(''); // Очищаем хэш
                $em->flush($data->user);
                
                $this->get('session')->getFlashBag()->add('success', '<b>Ваш пароль успешно изменен!</b><br>'
                    .'Теперь вы можете авторизоваться на сайте по своему e-mail и новому паролю');
                
                return $this->redirect($this->generateUrl('user_sign_in'));
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWUserBundle:User:new-password.html.twig', $data->toArray());
    }
    
    private function authorizeUser(User $user) {
        $request = $this->getRequest();
        
        /* Авторизация пользователя в системе */
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'auth', $user->getRoles());
        $this->get('security.context')->setToken($token);

        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $token);
        $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
        
        return TRUE;
    }
}
