<?php

namespace BW\UserBundle\Controller;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\Wallet;
use BW\UserBundle\Entity\Replenishment;
use BW\UserBundle\Form\ReplenishmentType;
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
        $request = $this->get('request');
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
    
    public function replenishAction() {
        $user = $this->getUser();
        if ( ! $user) {
            throw new AccessDeniedException('Доступ разрешен только авторизованным пользователям.');
        }
        
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        
        // get/create profile
        $profile = $user->getProfile();
        if ( ! $profile) {
            $profile = new Profile;
            $profile->setUser($user);
            $em->persist($profile);
            $em->flush();
        }
        
        $replenishment = new Replenishment;
        $form = $this->createForm(new ReplenishmentType, $replenishment);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                // Обработка квитанции
                $receipt = $replenishment->getReceipt();
                if ($receipt->getPath()) {
                    $em->persist($receipt);
                } else {
                    $replenishment->setReceipt(NULL);
                }
                
                $replenishment->setProfile($profile);
                $em->persist($replenishment);
                $wallet = $em->getRepository('BWUserBundle:Wallet')->findOneBy(array(
                    'profile' => $profile,
                    'currency' => $replenishment->getCurrency(),
                ));
                if ( ! $wallet) {
                    $wallet = new Wallet;
                    $wallet->setProfile($profile);
                    $wallet->setCurrency($replenishment->getCurrency());
                    $em->persist($wallet);
                }
                // Converting amount by exchange rate
                $replenishment->setEquivalentAmount(
                    $replenishment->getAdditiveAmount() / $replenishment->getCurrency()->getExchangeRate()
                );

                /**
                 * There is parameter in BWUserBundle/Resources/config/config.yml
                 * @var string $replenishment_mode = before_confirmation || after_confirmation
                 */
                $replenishment_mode = $this->get('service_container')->getParameter('replenishment_mode');
                if ($replenishment_mode === 'before_confirmation') {
                    // Add additive amount to the total amount
                    $wallet->setTotalAmount(
                        $wallet->getTotalAmount() + $replenishment->getEquivalentAmount()
                    );
                }

                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'Ваш кошелек успешно пополнен');

                return $this->redirect($this->generateUrl('user_wallet'));
            }
        }
        
        $data->form = $form->createView();
        return $this->render('BWUserBundle:Wallet:wallet-replenish.html.twig', $data->toArray());
    }
    
    public function replenishmentsAction() {
        $user = $this->getUser();
        if ( ! $user) {
            throw new AccessDeniedException('Доступ разрешен только авторизованным пользователям.');
        }
        
        $data = $this->getPropertyOverload();
        $em = $this->getDoctrine()->getManager();
        
        // get/create profile
        $profile = $user->getProfile();
        if ( ! $profile) {
            $profile = new Profile;
            $profile->setUser($user);
            $em->persist($profile);
            $em->flush();
        }
        
        $data->replenishments = $em->getRepository('BWUserBundle:Replenishment')->findBy(
                array(
                    'profile' => $profile,
                ),
                array(
                    'created' => 'DESC',
                )
            );
        
        return $this->render('BWUserBundle:Wallet:wallet-replenishments.html.twig', $data->toArray());
    }
    
}