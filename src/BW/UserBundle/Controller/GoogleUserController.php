<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Controller\UserController;
use BW\UserBundle\Entity\User;

class GoogleUserController extends UserController
{

    /**
     * Конструктор для GoogleUserController
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Doctrine\DBAL\Connection $conn
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Sign in with Google
     * @return redirect
     */
    public function signInAction() {
        /**
         * When we create the service here, we pass the
         * client to it. The client then queries the service
         * for the required scopes, and uses that when
         * generating the authentication URL later.
         */
        $request = $this->get('request');
        $session = $this->get('session');
        $client = $this->get('bw_user.social')->getGoogleClient();
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
//            if ($session->has('google_access_token')) {
//                $client->setAccessToken($session->get('google_access_token'));
//            }

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
                    $user = $this->getDoctrine()
                            ->getRepository('BWUserBundle:User')
                            ->loadUserBySocialId('googleId', $userInfo->id);
                    if ($user == NULL) {
                        // Проверка наличия email
                        $isEmailExists = $this->getDoctrine()
                                ->getRepository('BWUserBundle:User')
                                ->isEmailExists($userInfo->email);
                        if ($isEmailExists) {
                            $session->getFlashBag()->add('danger', '<b>Произошла ошибка!</b> Пользователь с e-mail "'. $userInfo->email .'" уже зарегистрирован в системе.');
                            return $this->redirect($this->generateUrl('home'));
                        }
                        // Проверка наличия username
                        $i = 0;
                        do {
                            $username = $userInfo->id . ($i ? '_'. $i : '');
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
                                ->setGoogleId($userInfo->id)
                                ->setEmail($userInfo->email)
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
            
            return $this->redirect($client->createAuthUrl());
        }
        
        return $this->redirect($this->generateUrl('home'));
    }
}
