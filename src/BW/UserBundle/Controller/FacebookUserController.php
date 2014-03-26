<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Controller\UserController;
use BW\UserBundle\Entity\User;

class FacebookUserController extends UserController
{

    /**
     * Конструктор для FacebookUserController
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     * @global \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Sign in with Facebook
     * @return redirect
     */
    public function signInAction() {
        $request = $this->get('request');
        $session = $this->get('session');
        // Facebook init
        $facebook = $this->get('bw_user.social')->getFacebook();
        
        if ($request->query->has('code')) {
            if ($facebook->getUser()) {
                $userProfile = $facebook->api('/me/');
                if ($userProfile) {
                    $user = $this->getDoctrine()
                            ->getRepository('BWUserBundle:User')
                            ->loadUserBySocialId('facebookId', $userProfile['id']);
                    if ($user == NULL) {
                        // Проверка наличия email
                        $isEmailExists = $this->getDoctrine()
                                ->getRepository('BWUserBundle:User')
                                ->isEmailExists($userProfile['email']);
                        if ($isEmailExists) {
                            $session->getFlashBag()->add('danger', '<b>Произошла ошибка!</b> Пользователь с e-mail "'. $userProfile['email'] .'" уже зарегистрирован в системе.');
                            return $this->redirect($this->generateUrl('home'));
                        }
                        // Проверка наличия username
                        $i = 0;
                        do {
                            $username = $userProfile['username'] . ($i ? '_'. $i : '');
                            $isUsernameExists = $this->getDoctrine()
                                    ->getRepository('BWUserBundle:User')
                                    ->isUsernameExists($username);
                            $i++;
                        } while ($isUsernameExists);
                        // Создание нового пользователя
                        $em = $this->getDoctrine()->getManager();
                        $role = $em->getRepository('BWUserBundle:Role')->findOneBy(array(
                            'role' => 'ROLE_USER',
                        ));
                        $user = new User();
                        $user
                                ->setFacebookId($userProfile['id'])
                                ->setEmail($userProfile['email'])
                                ->setUsername($username)
                                ->setActive(TRUE)
                                ->setConfirm(TRUE)
                                ->setHash('')
                                ->addRole($role)
                                ->generateRandomPassword()
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
            $social = $this->get('service_container')->getParameter('social');
            $params = array(
                'scope' => $social['facebook']['scopes'],
                'redirect_uri' => $this->get('router')->generate('user_facebook_sign_in', array(), TRUE),
            );
            
            return $this->redirect($facebook->getLoginUrl($params));
        }
        
        return $this->redirect($this->generateUrl('home'));
    }
}
