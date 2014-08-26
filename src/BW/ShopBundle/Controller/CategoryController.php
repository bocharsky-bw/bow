<?php

namespace BW\ShopBundle\Controller;

use BW\MainBundle\Utility\FormUtility;
use BW\RouterBundle\Entity\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\ShopBundle\Entity\Category;
use BW\ShopBundle\Form\CategoryType;

/**
 * Class CategoryController
 * @package BW\ShopBundle\Controller
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWShopBundle:Category')->findBy(array(), array(
            'left' => 'ASC',
        ));

        return $this->render('BWShopBundle:Category:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Category entity.
     */
    public function createAction(Request $request)
    {
        $entity = new Category();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            // Сгенерировать и упорядочить дерево Nested Set
            $this->get('bw_blog.nested_set')->regenerateTree(
                $em->getClassMetadata('BWShopBundle:Category')->getTableName() // Имя таблицы класса
            );

            if ($form->get('createAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('category'));
            }

            return $this->redirect($this->generateUrl('category_edit', array('id' => $entity->getId())));
        }

        return $this->render('BWShopBundle:Category:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Category entity.
     *
     * @param Category $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('category_create'),
            'method' => 'POST',
        ));

        FormUtility::addCreateButton($form);
        FormUtility::addCreateAndCloseButton($form);

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     */
    public function newAction()
    {
        $entity = new Category();
        $form   = $this->createCreateForm($entity);

        return $this->render('BWShopBundle:Category:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Category entity.
     */
    public function showAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $em->getRepository('BWShopBundle:Category')->createQueryBuilder('c');
        $qb
            ->addSelect('p')
            ->leftJoin('c.parent', 'p')
            ->where($qb->expr()->eq('c.published', true))
            ->andWhere($qb->expr()->eq('c.id', ':id'))
            ->setParameter('id', $id)
        ;
        $entity = $qb->getQuery()->getOneOrNullResult();
        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        /** @var \Doctrine\ORM\QueryBuilder $qb */
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
            ->andWhere('c.left >= :left AND c.left < :right')
            ->setParameter('left', $entity->getLeft())
            ->setParameter('right', $entity->getRight())
        ;
        $pagination = $this->get('knp_paginator')->paginate(
            $qb,
            $request->query->getInt('page', 1),
            $request->query->getInt('count', 10)
        );

        return $this->render('BWShopBundle:Category:show.html.twig', array(
            'entity' => $entity,
            'pagination' => $pagination,
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Category')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWShopBundle:Category:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Category entity.
     *
     * @param Category $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('category_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        FormUtility::addUpdateButton($form);
        FormUtility::addUpdateAndCloseButton($form);
        FormUtility::addDeleteButton($form);

        return $form;
    }

    /**
     * Edits an existing Category entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Category')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($editForm->get('delete')->isClicked()) {
                $this->delete($id);
                return $this->redirect($this->generateUrl('category'));
            }

            $em->flush();

            // Сгенерировать и упорядочить дерево Nested Set
            $this->get('bw_blog.nested_set')->regenerateTree(
                $em->getClassMetadata('BWShopBundle:Category')->getTableName() // Имя таблицы класса
            );

            if ($editForm->get('updateAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('category'));
            }

            return $this->redirect($this->generateUrl('category_edit', array('id' => $id)));
        }

        return $this->render('BWShopBundle:Category:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity by ID
     *
     * @param $id The entity ID
     */
    private function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BWShopBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $em->remove($entity);
        $em->flush();
    }

    /**
     * Deletes a Category entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->delete($id);
        }

        return $this->redirect($this->generateUrl('category'));
    }

    /**
     * Creates a form to delete a Category entity by id.
     *
     * @param mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

//    /**
//     * Finds and displays a Category entity.
//     */
//    public function showBySlugAction(Request $request, $slug)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        /** @var \Doctrine\ORM\QueryBuilder $qb */
//        $qb = $em->getRepository('BWShopBundle:Category')->createQueryBuilder('c');
//        $qb
//            ->addSelect('p')
//            ->leftJoin('c.parent', 'p')
//            ->where($qb->expr()->eq('c.published', true))
//            ->andWhere($qb->expr()->eq('c.slug', ':slug'))
//            ->setParameter('slug', $slug)
//        ;
//        $entity = $qb->getQuery()->getOneOrNullResult();
//        if ( ! $entity) {
//            throw $this->createNotFoundException('Unable to find Category entity.');
//        }
//
//        /** @var \Doctrine\ORM\QueryBuilder $qb */
//        $qb = $em->getRepository('BWShopBundle:Product')->createQueryBuilder('p');
//        $qb
//            ->addSelect('v')
//            ->addSelect('c')
//            ->addSelect('pi')
//            ->addSelect('i')
//            ->innerJoin('p.vendor', 'v')
//            ->leftJoin('p.category', 'c')
//            ->leftJoin('p.productImages', 'pi')
//            ->leftJoin('pi.image', 'i')
//            ->where($qb->expr()->eq('p.published', true))
//            ->andWhere('c.left >= :left AND c.left < :right')
//            ->setParameter('left', $entity->getLeft())
//            ->setParameter('right', $entity->getRight())
//        ;
//        $pagination = $this->get('knp_paginator')->paginate(
//            $qb,
//            $request->query->getInt('page', 1),
//            $request->query->getInt('count', 10)
//        );
//
//        return $this->render('BWShopBundle:Category:show.html.twig', array(
//            'entity' => $entity,
//            'pagination' => $pagination,
//        ));
//    }
}
