<?php

namespace BW\MenuBundle\Controller\Admin;

use BW\MainBundle\Controller\BWController;
use BW\MenuBundle\Entity\Item;
use BW\MenuBundle\Form\ItemType;

class ItemController extends BWController
{
    
    /**
     * @global \Symfony\Component\HttpFoundation\Request $request
     * @global \Symfony\Component\Form\Form $form
     * @global \Doctrine\ORM\EntityManager $em
     */
    public function __construct() {
        parent::__construct();
    }
    

    /**
     * Список всех пунктов меню
     * 
     * @return Response
     */
    public function itemsAction() {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        $criteria = array();
        if ($request->query->getInt('menu_id')) {
            $criteria['menu'] = $request->query->getInt('menu_id');
        }
        
        $data->items = $this->getDoctrine()->getRepository('BWMenuBundle:Item')->findBy(
            $criteria,
            array(
                'left' => 'ASC',
            )
        );
        
//        $recursion = new \BW\MenuBundle\Service\Recursion();
//        $data->items = $recursion->levelParentEntityRecursion($items);
        
        return $this->render('BWMenuBundle:Admin/Item:items.html.twig', $data->toArray());
    }
    
    /***
     * Создание нового пункта меню / Редактирование пункта меню по его ID
     * 
     * @return Response
     */
    public function itemAction($id = NULL) {
        $data = $this->getPropertyOverload();
        $request = $this->get('request');
        
        if ($id) {
            $item = $this->getDoctrine()->getRepository('BWMenuBundle:Item')->find($id);
        } else {
            $item = new Item();
        }
        
        /* Выбор типа пункта меню */
        if ($request->query->get('type')) {
            $item->setType( $this->getDoctrine()->getRepository('BWMenuBundle:Type')->findOneBy(array('alias' => $request->query->get('type'))) );
        }
        if ($item->getType()) {
            $itemTypeArray = explode('_', $item->getType()->getAlias());
            
            $itemTypeClassname = '\BW\MenuBundle\Form\ItemTypes\Item';
            foreach ($itemTypeArray as $val) {
                $itemTypeClassname .= ucfirst($val);
            }
            $itemTypeClassname .= 'Type';
            
            $data->itemTypeFilenamePath = 'BWMenuBundle:Admin/Item/types:item-'. str_replace('_', '-', $item->getType()->getAlias()) .'.html.twig';
        }
        else {
            $itemTypeClassname = '\BW\MenuBundle\Form\ItemType';
        }
        
        /* Восстанавливаем ссылку */
        if ($item->getRoute()) {
            $item->setHref($this->generateUrl('bw_router_index', array('q' => $item->getRoute()->getQuery()), TRUE));
        }
        
        $form = $this->createForm(new $itemTypeClassname(), $item);
        if ( ! $id) {
            $form->remove('delete');
        }
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                if ($id) {
                    if ($form->get('delete')->isClicked()) {
                        $em->remove($item);
                        $em->flush();
                        
                        $this->get('session')->getFlashBag()->add(
                            'danger',
                            'Пункт меню успешно удален'
                        );

                        return $this->redirect($this->generateUrl('admin_items'));
                    }
                }
                
                /* Привязка к роуту */
                if ($item->getHref()) {
                    $path = str_replace(
                        $this->generateUrl('home_root', array(), TRUE), '', $item->getHref()
                    );
                    $route = $em->getRepository('BWRouterBundle:Route')->findOneBy(
                        array(
                            'path' => $path,
                        )
                    );
                    if ($route) {
                        $item->setHref('');
                        $item->setRoute($route);
                    } else {
                        $item->setHref(
                            preg_replace('/^\w{2}\//i', '', $path) // Удаление языка из адреса
                        );
                        $item->setRoute(NULL);
                    }
                }
                
                $em->persist($item);
                $em->flush();
                
                // Сгенерировать и упорядочить дерево Nested Set
                $this->get('bw_blog.nested_set')->regenerateTree(
                        $em->getClassMetadata('BWMenuBundle:Item')->getTableName() // Имя таблицы класса
                    );
                
                $this->get('session')->getFlashBag()->add(
                        'success',
                        'Пункт меню успешно сохранен'
                    );
                
                if ($form->get('saveAndExit')->isClicked()) {
                    return $this->redirect($this->generateUrl('admin_items', array('menu_id' => $item->getMenu()->getId())));
                }
                
                return $this->redirect($this->generateUrl('admin_item_edit', array('id' => $item->getId())));
            }
        }
        
        $data->item = $item;
        $data->form = $form->createView();
        if ($id) {
            return $this->render('BWMenuBundle:Admin/Item:item-edit.html.twig', $data->toArray());
        }
        
        return $this->render('BWMenuBundle:Admin/Item:item-add.html.twig', $data->toArray());
    }
    
    public function deleteAction($id) {
        
    }
}
