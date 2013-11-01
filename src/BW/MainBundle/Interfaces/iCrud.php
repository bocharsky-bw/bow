<?php

namespace BW\MainBundle\Interfaces;

interface iCrud {
    
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Symfony\Component\Form\Form $form
     * @global \Doctrine\ORM\EntityManager $em
     */
    public function __construct();
    
    public function listAction();
    
    public function addAction();
    
    public function editAction($id);
    
    public function deleteAction($id);
}