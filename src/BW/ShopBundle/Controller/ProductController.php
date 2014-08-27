<?php

namespace BW\ShopBundle\Controller;

use BW\MainBundle\Utility\FormUtility;
use BW\ShopBundle\Entity\Category;
use BW\ShopBundle\Entity\Vendor;
use Doctrine\Common\Collections\ArrayCollection;
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
     * Lists all Category entities.
     */
    public function listAction(Request $request)
    {
        $builder = $this->createFormBuilder(null, array(
            'csrf_protection' => false,
        ))->setMethod('GET');
        $builder
            ->add('vendor', 'entity', array(
                'class' => 'BW\ShopBundle\Entity\Vendor',
                'property' => 'heading',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Производитель',
            ))
            ->add('category', 'entity', array(
                'class' => 'BW\ShopBundle\Entity\Category',
                'property' => 'heading',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Категория ',
            ))
            ->add('apply', 'submit', array(
                'label' => 'Применить',
            ))
        ;
        $form = $builder->getForm();
        $form->handleRequest($request);
        $url = array();
        $queryForFilter = array();
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();
                foreach ($data as $block) {
                    if ($block instanceof ArrayCollection) {
                        $count = $block->count();
                        if (1 === $count) {
                            $entity = $block->first();
                            if ($entity instanceof Vendor) {
                                $url[] = $this->generateUrl('vendor_show_by_slug', array(
                                    'slug' => $entity->getSlug(),
                                ), true);
                            } elseif ($entity instanceof Category) {
                                $url[] = $request->getUriForPath($entity->getRoute()->getPath());
                            }
                        } elseif (1 < $count) {
                            $ids = array();
                            foreach ($block as $vendor) {
                                $ids[] = $vendor->getId();
                            }
                            $queryForFilter[] = implode('-', $ids);
                        }
                    }
                }
            }
        }
        var_dump($url);
        var_dump($queryForFilter);
        if (isset($url[0])) {
            $url = $url[0];
        }
        var_dump($url);




        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BWShopBundle:Product')->findBy(array(
            'published' => true,
        ), array(
//            'created' => 'ASC',
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
