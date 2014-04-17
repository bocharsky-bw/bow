<?php

namespace BW\UserBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use BW\UserBundle\Entity\Replenishment;
use BW\UserBundle\Form\ReplenishmentType;

class ReplenishmentController extends BWController
{
    /**
     * Список всех пользователей
     * 
     * @return render
     */
    public function replenishmentsAction() {
        $data = $this->getPropertyOverload();
        
        $data->replenishments = $this->getDoctrine()->getRepository('BWUserBundle:Replenishment')->findBy(
            array(),
            array(
                'created' => 'desc',
            )
        );
        
        return $this->render('BWUserBundle:Admin/Replenishment:replenishments.html.twig', $data->toArray());
    }
    
    public function replenishmentConfirmAction($id) {
        $replenishment = $this->getDoctrine()->getRepository('BWUserBundle:Replenishment')->find($id);
        
        if ($replenishment) {
            if ($replenishment->getStatus() == 0) {
                $replenishment->setStatus(1);
                $this->getDoctrine()
                        ->getManager()
                        ->flush()
                    ;
                $this->getRequest()
                        ->getSession()
                        ->getFlashBag()
                        ->add('success', "Пополнение с ID = {$replenishment->getId()} успешно подтверждено")
                    ;
            }
        }
        
        return $this->redirect($this->generateUrl('admin_replenishments'));
    }
    
    public function replenishmentRejectAction($id) {
        $replenishment = $this->getDoctrine()->getRepository('BWUserBundle:Replenishment')->find($id);
        
        if ($replenishment) {
            if ($replenishment->getStatus() == 0) {
                $wallet = $this->getDoctrine()
                        ->getRepository('BWUserBundle:Wallet')
                        ->findOneBy(
                            array(
                                'profile' => $replenishment->getProfile(),
                                'currency' => $replenishment->getCurrency(),
                            )
                        )
                    ;
                $wallet->setTotalAmount(
                        $wallet->getTotalAmount() - $replenishment->getEquivalentAmount()
                    );
                $replenishment->setStatus(2);
                $this->getDoctrine()
                        ->getManager()
                        ->flush()
                    ;
                $this->getRequest()
                        ->getSession()
                        ->getFlashBag()
                        ->add('danger', "Пополнение с ID = {$replenishment->getId()} успешно отклонено")
                    ;
            }
        }
                
        return $this->redirect($this->generateUrl('admin_replenishments'));
    }
    
}
