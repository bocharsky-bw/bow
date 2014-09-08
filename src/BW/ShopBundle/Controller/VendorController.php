<?php

namespace BW\ShopBundle\Controller;

use BW\MainBundle\Utility\FormUtility;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BW\ShopBundle\Entity\Vendor;
use BW\ShopBundle\Form\VendorType;

/**
 * Class VendorController
 * @package BW\ShopBundle\Controller
 */
class VendorController extends Controller
{

    /**
     * Lists all Vendor entities in frontend.
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWShopBundle:Vendor')->findBy(array());

        return $this->render('BWShopBundle:Vendor:list.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all Vendor entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWShopBundle:Vendor')->findAll();

        return $this->render('BWShopBundle:Vendor:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Vendor entity.
     */
    public function createAction(Request $request)
    {
        $entity = new Vendor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($form->get('createAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('vendor'));
            }

            return $this->redirect($this->generateUrl('vendor_edit', array('id' => $entity->getId())));
        }

        return $this->render('BWShopBundle:Vendor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Vendor entity.
     *
     * @param Vendor $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Vendor $entity)
    {
        $form = $this->createForm(new VendorType(), $entity, array(
            'action' => $this->generateUrl('vendor_create'),
            'method' => 'POST',
        ));

        FormUtility::addCreateButton($form);
        FormUtility::addCreateAndCloseButton($form);

        return $form;
    }

    /**
     * Displays a form to create a new Vendor entity.
     */
    public function newAction()
    {
        $entity = new Vendor();
        $form   = $this->createCreateForm($entity);

        return $this->render('BWShopBundle:Vendor:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Vendor entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Vendor')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWShopBundle:Vendor:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Vendor entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Vendor')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWShopBundle:Vendor:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Vendor entity.
     *
     * @param Vendor $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Vendor $entity)
    {
        $form = $this->createForm(new VendorType(), $entity, array(
            'action' => $this->generateUrl('vendor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        FormUtility::addUpdateButton($form);
        FormUtility::addUpdateAndCloseButton($form);
        FormUtility::addDeleteButton($form);

        return $form;
    }

    /**
     * Edits an existing Vendor entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Vendor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            if ($editForm->get('delete')->isClicked()) {
                $this->delete($id);
                return $this->redirect($this->generateUrl('vendor'));
            }

            $em->flush();

            if ($editForm->get('updateAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('vendor'));
            }

            return $this->redirect($this->generateUrl('vendor_edit', array('id' => $id)));
        }

        return $this->render('BWShopBundle:Vendor:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BWShopBundle:Vendor')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }

        $em->remove($entity);
        $em->flush();
    }

    /**
     * Deletes a Vendor entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->delete($id);
        }

        return $this->redirect($this->generateUrl('vendor'));
    }

    /**
     * Creates a form to delete a Vendor entity by id.
     *
     * @param mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vendor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    /**
     * Finds and displays a Vendor entity by slug.
     */
    public function showBySlugAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('BWShopBundle:Vendor')->createQueryBuilder('v');
        $qb
            ->addSelect('i')
            ->leftJoin('v.image', 'i')
            ->where($qb->expr()->eq('v.slug', ':slug'))
            ->setParameter('slug', $slug)
        ;
        /** @var Vendor $entity */
        $entity = $qb->getQuery()->getOneOrNullResult();
        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Vendor entity.');
        }

        $filter = $this->get('bw_shop.service.product_filter');
        $form = $filter->createProductFilterForm(array(
            $entity->getCustomFieldProperty(),
        ));

        $qb = $em->getRepository('BWShopBundle:Product')->createQueryBuilder('p');
        $qb
            ->addSelect('v')
            ->addSelect('c')
            ->addSelect('pi')
            ->addSelect('i')
            ->innerJoin('p.vendor', 'v')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.productImages', 'pi')
            ->leftJoin('pi.image', 'i')
            ->where($qb->expr()->eq('p.published', true))
            ->andWhere($qb->expr()->eq('p.vendor', $entity->getId()))
        ;
        $pagination = $this->get('knp_paginator')->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $request->query->getInt('count', 10)
        );

        return $this->render('BWShopBundle:Vendor:show.html.twig', array(
            'entity' => $entity,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ));
    }
}
