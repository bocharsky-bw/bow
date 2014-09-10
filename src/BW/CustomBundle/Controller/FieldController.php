<?php

namespace BW\CustomBundle\Controller;

use BW\CustomBundle\Entity\Field;
use BW\CustomBundle\Form\FieldType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FieldController extends Controller
{
    public function fieldsAction()
    {
        $entities = $this->getDoctrine()->getRepository('BWCustomBundle:Field')->findAll();

        return $this->render('BWCustomBundle:Field:list.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function fieldAction(Request $request, $id = NULL)
    {
        if ($id) {
            $entity = $this->getDoctrine()->getRepository('BWCustomBundle:Field')->find($id);
        } else {
            $entity = new Field();
        }

        $form = $this->createForm(new FieldType(), $entity);
        if ( ! $id) {
            $form->remove('delete');
        }

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                if ($id) {
                    if ( $form->get('delete')->isClicked() ) {
                        $em->remove($entity);
                        $em->flush();

                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Настраиваемое поле успешно удалено из БД'
                        );

                        return $this->redirect( $this->generateUrl('admin_custom_fields') );
                    }
                }

                if ( ! $entity->getId()) {
                    $em->persist($entity);
                }
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Настраиваемое поле успешно сохранено в БД'
                );

                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect( $this->generateUrl('admin_custom_fields') );
                }

                return $this->redirect( $this->generateUrl('admin_custom_field_edit', array('id' => $entity->getId())) );
            }
        }

        if ($id) {
            return $this->render('BWCustomBundle:Field:edit.html.twig', array(
                'entity' => $entity,
                'form' => $form->createView(),
            ));
        }

        return $this->render('BWCustomBundle:Field:add.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

}
