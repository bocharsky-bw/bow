<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Controller\UserController;
use BW\UserBundle\Entity\User;

class VkontakteUserController extends UserController
{

    /**
     * Конструктор для VkontakteUserController
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     * @global \BW\Vkontakte $vk
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Sign in with Vkontakte
     * @return redirect
     */
    public function signInAction() {
        $request = $this->get('request');
        $session = $this->get('session');

        // Vkontakte init
        $vk = $this->get('bw_user.social')->getVkontakte();
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
                ));
                $userProfile = $userProfile[0];

                if ($userProfile->uid) {
                    $user = $this->getDoctrine()
                            ->getRepository('BWUserBundle:User')
                            ->loadUserBySocialId('vkontakteId', $userProfile->uid);
                    if ($user == NULL) {
                        // Проверка наличия username
                        $i = 0;
                        do {
                            $username = $userProfile->domain . ($i ? '_'. $i : '');
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
                                ->setVkontakteId($userProfile->uid)
                                ->setEmail(NULL)
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
            // Авторизируем пользователя
            return $this->redirect($vk->getLoginUrl());
        }
        
        return $this->redirect($this->generateUrl('home'));
    }
}
