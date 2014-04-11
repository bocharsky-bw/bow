<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\Wallet;
use BW\UserBundle\Form\WalletReplenishType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class WalletController extends BWController
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

    
    public function walletAction() {
        $user = $this->getUser();
        if ( ! $user) {
            throw new AccessDeniedException('Доступ разрешен только авторизованным пользователям.');
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        
        // get/create profile
        $profile = $user->getProfile();
        if ( ! $profile) {
            $profile = new Profile;
            $profile->setUser($user);
            $em->persist($profile);
            $em->flush();
        }
        
        $data->currencies = $this->getDoctrine()
                ->getRepository('BWUserBundle:Currency')
                ->findBy(
                        array(),
                        array()
                    );
            ;
        $data->wallets = $this->getDoctrine()
                ->getRepository('BWUserBundle:Wallet')
                ->findBy(
                        array(
                            'profile' => $profile,
                        ),
                        array()
                    );
            ;
        
        return $this->render('BWUserBundle:Wallet:wallet.html.twig', $data->toArray());
    }
    
    public function walletReplenishAction() {
        $user = $this->getUser();
        if ( ! $user) {
            throw new AccessDeniedException('Доступ разрешен только авторизованным пользователям.');
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        
        // get/create profile
        $profile = $user->getProfile();
        if ( ! $profile) {
            $profile = new Profile;
            $profile->setUser($user);
            $em->persist($profile);
            $em->flush();
        }
        
        $form = $this->createFormBuilder()
                ->add('additiveAmount', 'number')
                ->add('currency', 'entity', array(
                    'class' => 'BW\UserBundle\Entity\Currency',
                    'property' => 'name',
                ))
                ->add('replenish', 'submit')
                ->getForm()
            ;
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formData = $form->getData();
                $wallet = $em->getRepository('BWUserBundle:Wallet')->findOneBy(array(
                    'profile' => $profile,
                    'currency' => $formData['currency'],
                ));
                if ( ! $wallet) {
                    $wallet = new Wallet;
                    $wallet->setProfile($profile);
                    $wallet->setCurrency($formData['currency']);
                    $em->persist($wallet);
                    $em->flush();
                }
                // Converting amount by exchange rate
                $amount = $formData['additiveAmount'] / $formData['currency']->getExchangeRate();
                // Add additive amount to the total amount
                $amount = $wallet->getTotalAmount() + $amount;
                $wallet->setTotalAmount($amount);
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Ваш кошелек успешно пополнен');

                return $this->redirect($this->generateUrl('user_wallet'));
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWUserBundle:Wallet:wallet-replenish.html.twig', $data->toArray());
    }
    
}