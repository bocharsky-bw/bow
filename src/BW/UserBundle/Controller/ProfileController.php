<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\Profile;
use BW\UserBundle\Form\ProfilePersonalType;
use BW\UserBundle\Form\ProfileAddressType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BWController
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

    
    public function personalAction() {
        $user = $this->getUser();
        if ( ! $user) {
            throw new AccessDeniedException('Доступ разрешен только авторизованным пользователям.');
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        
        $profile = $user->getProfile();
        if ( ! $profile) {
            $profile = new Profile;
            $profile->setUser($user);
            $em->persist($profile);
            $em->flush();
        }
        
        $form = $this->createForm(new ProfilePersonalType($userForm), $profile);
        $form->add('user', new \BW\UserBundle\Form\ProfilePasswordChangeType());
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Информация успешно сохранена.');
                
                return $this->redirect( $this->generateUrl('user_profile_personal') );
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWUserBundle:Profile:personal.html.twig', $data->toArray());
    }
    
    public function addressAction() {
        $user = $this->getUser();
        if ( ! $user) {
            throw new AccessDeniedException('Доступ разрешен только авторизованным пользователям.');
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        
        $profile = $user->getProfile();
        if ( ! $profile) {
            $profile = new Profile;
            $profile->setUser($user);
            $em->persist($profile);
            $em->flush();
        }
        
        $form = $this->createForm(new ProfileAddressType(), $profile);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $request->getSession()->getFlashBag()->add('success', 'Информация успешно сохранена.');
                $em->flush();
                
                return $this->redirect( $this->generateUrl('user_profile_address') );
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWUserBundle:Profile:address.html.twig', $data->toArray());
    }
    
}