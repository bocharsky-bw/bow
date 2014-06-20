<?php

namespace BW\GalleryBundle\Controller;

use BW\MainBundle\Utility\FormUtility;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\GalleryBundle\Entity\Gallery;
use BW\GalleryBundle\Form\GalleryType;

/**
 * Class GalleryController
 * @package BW\GalleryBundle\Controller
 */
class GalleryController extends Controller
{

    /**
     * Lists all Gallery entities.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWGalleryBundle:Gallery')->findAll();

        return $this->render('BWGalleryBundle:Gallery:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Gallery entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWGalleryBundle:Gallery')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Запрашиваемая галерея не существует.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWGalleryBundle:Gallery:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Gallery entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $entity = new Gallery();
        $form   = $this->createCreateForm($entity);

        return $this->render('BWGalleryBundle:Gallery:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Gallery entity.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Gallery();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($form->get('createAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('gallery'));
            }

            return $this->redirect($this->generateUrl('gallery_edit', array('id' => $entity->getId())));
        }

        return $this->render('BWGalleryBundle:Gallery:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Gallery entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWGalleryBundle:Gallery')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Запрашиваемая галерея не существует.');
        }

        $form = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWGalleryBundle:Gallery:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Gallery entity.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWGalleryBundle:Gallery')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Запрашиваемая галерея не существует.');
        }

        $form = $this->createEditForm($entity);
        $form->handleRequest($request);
        if ($form->get('delete')->isClicked()) {
            $this->delete($id);

            return $this->redirect($this->generateUrl('gallery'));
        }
        if ($form->isValid()) {
            $em->flush();

            if ($form->get('updateAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('gallery'));
            }

            return $this->redirect($this->generateUrl('gallery_edit', array('id' => $id)));
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWGalleryBundle:Gallery:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete Gallery object from DB
     *
     * @param $id The Gallery id
     * @return bool
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BWGalleryBundle:Gallery')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Запрашиваемая галерея не существует.');
        }

        $em->remove($entity);
        $em->flush();

        return true;
    }

    /**
     * Deletes a Gallery entity.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->delete($id);
        }

        return $this->redirect($this->generateUrl('gallery'));
    }

    /**
     * Creates a form to create a Gallery entity.
     *
     * @param Gallery $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Gallery $entity)
    {
        $form = $this->createForm(new GalleryType(), $entity, array(
            'action' => $this->generateUrl('gallery_create'),
            'method' => 'POST',
        ));

        FormUtility::addCreateButton($form);
        FormUtility::addCreateAndCloseButton($form);

        return $form;
    }

    /**
     * Creates a form to edit a Gallery entity.
     *
     * @param Gallery $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Gallery $entity)
    {
        $form = $this->createForm(new GalleryType(), $entity, array(
            'action' => $this->generateUrl('gallery_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        FormUtility::addUpdateButton($form);
        FormUtility::addUpdateAndCloseButton($form);
        FormUtility::addDeleteButton($form);

        return $form;
    }

    /**
     * Creates a form to delete a Gallery entity by id.
     *
     * @param mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('gallery_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;

        FormUtility::addDeleteButton($form);

        return $form;
    }
}
