<?php

namespace BW\GalleryBundle\Controller;

use BW\MainBundle\Utility\FormUtility;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use BW\GalleryBundle\Entity\Photo;
use BW\GalleryBundle\Form\PhotoType;

/**
 * Photo controller.
 *
 */
class PhotoController extends Controller
{
    /**
     * Lists all Photo entities.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWGalleryBundle:Photo')->findAll();

        return $this->render('BWGalleryBundle:Photo:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Photo entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWGalleryBundle:Photo')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Запрашиваемая фотография не существует.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWGalleryBundle:Photo:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction()
    {
        $entity = new Photo();
        $form   = $this->createCreateForm($entity);

        return $this->render('BWGalleryBundle:Photo:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Photo entity.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new Photo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($form->get('createAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('gallery_edit', array(
                    'id' => $entity->getGallery()->getId(),
                )));
            }

            return $this->redirect($this->generateUrl('photo_edit', array('id' => $entity->getId())));
        }

        return $this->render('BWGalleryBundle:Photo:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Photo entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWGalleryBundle:Photo')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Запрашиваемая фотография не существует.');
        }

        $form = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWGalleryBundle:Photo:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Photo entity.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWGalleryBundle:Photo')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Запрашиваемая фотография не существует.');
        }

        $form = $this->createEditForm($entity);
        $form->handleRequest($request);
        if ($form->get('delete')->isClicked()) {
            $this->delete($id);

            return $this->redirect($this->generateUrl('gallery_edit', array(
                'id' => $entity->getGallery()->getId(),
            )));
        }
        if ($form->isValid()) {
            $em->flush();

            if ($form->get('updateAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('gallery_edit', array(
                    'id' => $entity->getGallery()->getId(),
                )));
            }

            return $this->redirect($this->generateUrl('photo_edit', array('id' => $id)));
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWGalleryBundle:Photo:edit.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete Photo object from DB
     *
     * @param $id The Photo id
     * @return bool
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BWGalleryBundle:Photo')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Запрашиваемая фотография не существует.');
        }

        $em->remove($entity);
        $em->flush();

        return true;
    }

    /**
     * Deletes a Photo entity.
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

        return $this->redirect($this->generateUrl('photo'));
    }

    /**
     * Creates a form to create a Photo entity.
     *
     * @param Photo $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Photo $entity)
    {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_create'),
            'method' => 'POST',
        ));

        FormUtility::addCreateButton($form);
        FormUtility::addCreateAndCloseButton($form);

        return $form;
    }

    /**
     * Creates a form to edit a Photo entity.
     *
     * @param Photo $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Photo $entity)
    {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        FormUtility::addUpdateButton($form);
        FormUtility::addUpdateAndCloseButton($form);
        FormUtility::addDeleteButton($form);

        return $form;
    }

    /**
     * Creates a form to delete a Photo entity by id.
     *
     * @param mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('photo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;

        FormUtility::addDeleteButton($form);

        return $form;
    }
}
