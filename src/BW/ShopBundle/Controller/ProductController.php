<?php

namespace BW\ShopBundle\Controller;

use BW\BlogBundle\Entity\CustomFieldProperty;
use BW\MainBundle\Utility\FormUtility;
use BW\ShopBundle\Entity\Category;
use BW\ShopBundle\Entity\Vendor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BW\ShopBundle\Entity\Product;
use BW\ShopBundle\Form\ProductType;

/**
 * Class ProductController
 * @package BW\ShopBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function filterFormHandleAction(Request $request)
    {
        $filter = $this->get('bw_shop.service.product_filter');

        $form = $filter->getProductFilterForm();
        if ($form->isValid()) {
            $redirectUrl = $filter->generateUrl();
            $content = $request->getContent(); // Использовать setContent()
            var_dump(urldecode($content));
            die('d');

            var_dump($redirectUrl); /** @TODO DEBUG */

            return $this->redirect($redirectUrl);
        } else {
            die('form invalid');
        }

    }

    /**
     * Filter the product list
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function filterAction(Request $request, $filter_query)
    {
        $session = $request->getSession();
        $filter = $this->get('bw_shop.service.product_filter');
        $em = $this->getDoctrine()->getManager();

        /////////////////
        $filter_query = preg_replace('@\-\*$@', '', $filter_query);
        $ids = explode('-', $filter_query);
        $properties = $em->getRepository('BWBlogBundle:CustomFieldProperty')->findBy(array(
            'id' => $ids,
        ));
        $fields = array();
        /** @var CustomFieldProperty $property */
        foreach($properties as $property) {
            $fields[$property->getCustomField()->getId()][] = $property->getId();
        }
        $content = '';
        var_dump($fields);
        foreach ($fields as $fieldId => $properties) {
            foreach ($properties as $propertyId) {
                $content .= "form[{$fieldId}][]={$propertyId}&";
            }
        }
        $content .= 'form[apply]=';
        /////////////////

        $form = $filter->createProductFilterForm();
        $form->handleRequest($request);

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository('BWShopBundle:Product')->createQueryBuilder('p');
        $qb
            ->where($qb->expr()->eq('p.published', true))
            ->orderBy('p.created', 'ASC')
        ;

        $entities = $qb->getQuery()->getResult();

        return $this->render('BWShopBundle:Product:list.html.twig', array(
            'entities' => $entities,
            'form' => $form->createView(),
        ));
    }

    /**
     * Lists all Category entities.
     */
    public function listAction(Request $request)
    {
        $filter = $this->get('bw_shop.service.product_filter');

        $form = $filter->getProductFilterForm();
        if ($form->isValid()) {
            $redirectUrl = $filter->generateUrl();
            var_dump($redirectUrl); /** @TODO DEBUG */

            return $this->redirect($redirectUrl);
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWShopBundle:Product')->findBy(array(
            'published' => true,
        ), array(
            'created' => 'ASC',
        ));

        return $this->render('BWShopBundle:Product:list.html.twig', array(
            'entities' => $entities,
            'form' => $form->createView(),
        ));
    }

//    /**
//     * Finds and displays a Product entity by slug.
//     */
//    public function showBySlugAction($slug)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('BWShopBundle:Product')->findOneBySlug($slug);
//
//        if ( ! $entity) {
//            throw $this->createNotFoundException('Unable to find Product entity.');
//        }
//
//        return $this->render('BWShopBundle:Product:show.html.twig', array(
//            'entity'      => $entity,
//        ));
//    }

    /**
     * Lists all Product entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWShopBundle:Product')->findAll();

        return $this->render('BWShopBundle:Product:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Product entity.
     */
    public function createAction(Request $request)
    {
        $entity = new Product();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            if ($form->get('createAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('product'));
            }

            return $this->redirect($this->generateUrl('product_edit', array('id' => $entity->getId())));
        }

        return $this->render('BWShopBundle:Product:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Product entity.
     *
     * @param Product $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('product_create'),
            'method' => 'POST',
        ));

        FormUtility::addCreateButton($form);
        FormUtility::addCreateAndCloseButton($form);

        return $form;
    }

    /**
     * Displays a form to create a new Product entity.
     */
    public function newAction()
    {
        $entity = new Product();
        $form   = $this->createCreateForm($entity);

        return $this->render('BWShopBundle:Product:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Product entity.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Product')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWShopBundle:Product:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BWShopBundle:Product:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Product entity.
     *
     * @param Product $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Product $entity)
    {
        $form = $this->createForm(new ProductType(), $entity, array(
            'action' => $this->generateUrl('product_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        FormUtility::addUpdateButton($form);
        FormUtility::addUpdateAndCloseButton($form);
        FormUtility::addDeleteButton($form);

        return $form;
    }

    /**
     * Edits an existing Product entity.
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BWShopBundle:Product')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($editForm->get('delete')->isClicked()) {
                $this->delete($id);
                return $this->redirect($this->generateUrl('product'));
            }

            $em->flush();

            if ($editForm->get('updateAndClose')->isClicked()) {
                return $this->redirect($this->generateUrl('product'));
            }

            return $this->redirect($this->generateUrl('product_edit', array('id' => $id)));
        }

        return $this->render('BWShopBundle:Product:edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    private function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BWShopBundle:Product')->find($id);

        if ( ! $entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $em->remove($entity);
        $em->flush();
    }

    /**
     * Deletes a Product entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->delete($id);
        }

        return $this->redirect($this->generateUrl('product'));
    }

    /**
     * Creates a form to delete a Product entity by id.
     *
     * @param mixed $id The entity id
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
            ;
    }
}
