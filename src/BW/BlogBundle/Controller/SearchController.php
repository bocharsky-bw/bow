<?php

namespace BW\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {
        $request = $this->get('request');
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->setAction($this->generateUrl('search'))
            ->add('query', 'text', array(
                'label' => ' ',
                'attr' => array(
                    'placeholder' => 'Что ищем?..',
                ),
            ))
            ->add('search', 'submit', array(
                'label' => 'Найти'
            ))
            ->getForm()
        ;

        $results = array();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $query = $data['query'];

            $searchBy = array(
                'entities' => array(
                    array(
                        'class' => 'BWBlogBundle:Post',
                        'properties' => array(
                            'heading',
                            'shortDescription',
                            'content',
                        ),
                    ),
                    array(
                        'class' => 'BWBlogBundle:Category',
                        'properties' => array(
                            'heading',
                            'shortDescription',
                            'description',
                        ),
                    ),
                ),
            );

            foreach ($searchBy as $mode => $target) {
                switch ($mode) {
                    case 'entities': {
                        foreach ($target as $index => $entity) {
                            $alias = 'a' . $index;
                            $qb = $em->getRepository($entity['class'])->createQueryBuilder($alias);
                            foreach ($entity['properties'] as $property) {
                                $qb->orWhere("{$alias}.{$property} LIKE :query");
                            }
                            $qb->setParameter("query", "%{$query}%", \PDO::PARAM_STR);

                            $results[$entity['class']] = $qb->getQuery()->getResult();
                        }

                        break;
                    }
                }
            }
        }

        return $this->render('BWBlogBundle:Search:index.html.twig', array(
            'form' => $form->createView(),
            'results' => $results,
            'isFormSubmitted' => $form->isSubmitted(),
        ));
    }
}
