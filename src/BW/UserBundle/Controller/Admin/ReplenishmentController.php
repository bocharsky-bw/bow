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
    
    public function replenishmentToggleAction($id) {
        $replenishment = $this->getDoctrine()->getRepository('BWUserBundle:Replenishment')->find($id);
        
        if ($replenishment) {
            $status = ! $replenishment->getConfirmed();
            $replenishment->setConfirmed($status);
            $this->getDoctrine()->getManager()->flush();
            if ($status) {
                $this->getRequest()
                        ->getSession()
                        ->getFlashBag()
                        ->add('success', "Пополнение с ID = {$replenishment->getId()} успешно подтверждено")
                    ;
            } else {
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
