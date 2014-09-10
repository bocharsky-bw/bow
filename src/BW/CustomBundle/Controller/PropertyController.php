<?php

namespace BW\CustomBundle\Controller;

use BW\CustomBundle\Entity\Property;
use BW\CustomBundle\Form\PropertyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PropertyController extends Controller
{
    public function propertyAction(Request $request, $field_id, $id = NULL)
    {
        if ($id) {
            $entity = $this->getDoctrine()->getRepository('BWCustomBundle:Property')->find($id);
        } else {
            $entity = new Property();
        }
        $entity->setField(
            $this->getDoctrine()->getRepository('BWCustomBundle:Field')->find($field_id)
        );


        $form = $this->createForm(new PropertyType(), $entity);
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
                            'Свойство успешно удалено из БД'
                        );

                        return $this->redirect($this->generateUrl('admin_custom_field_edit', array(
                            'id' => $field_id,
                        )));
                    }
                }

                if ( ! $entity->getId()) {
                    $em->persist($entity);
                }
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'Свойство успешно сохранено в БД'
                );

                if ( $form->get('saveAndClose')->isClicked() ) {
                    return $this->redirect($this->generateUrl('admin_custom_field_edit', array(
                        'id' => $field_id,
                    )));
                }

                return $this->redirect( $this->generateUrl('admin_custom_field_property_edit', array(
                    'field_id' => $field_id,
                    'id' => $entity->getId(),
                )));
            }
        }


        if ($id) {
            return $this->render('BWCustomBundle:Property:edit.html.twig', array(
                'entity' => $entity,
                'form' => $form->createView(),
            ));
        }

        return $this->render('BWCustomBundle:Property:add.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

}
