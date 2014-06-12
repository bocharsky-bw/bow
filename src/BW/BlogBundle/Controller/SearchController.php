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
                        ),
                    ),
                    array(
                        'class' => 'BWBlogBundle:Category',
                        'properties' => array(
                            'heading',
                        ),
                    ),
                ),
            );

            foreach ($searchBy as $mode => $target) {
                switch ($mode) {
                    case 'entities':
                        foreach ($target as $index => $entity) {
                            $alias = 'a' . $index;
                            $results[$entity['class']] = $em->getRepository($entity['class'])
                                ->createQueryBuilder($alias)
                                ->where("$alias.heading LIKE :query")
                                ->setParameter('query', '%' . $query . '%', \PDO::PARAM_STR)
                                ->getQuery()
                                ->getResult()
                            ;
                        }

                        break;
                }
            }


//            $results['Category'] = $em->getRepository('BWBlogBundle:Category')
//                ->createQueryBuilder('c')
//                ->where('c.heading LIKE :query')
//                ->setParameter('query', '%' . $query . '%', \PDO::PARAM_STR)
//                ->getQuery()
//                ->getResult()
//            ;

        }

        return $this->render('BWBlogBundle:Search:index.html.twig', array(
            'form' => $form->createView(),
            'results' => $results,
            'isFormSubmitted' => $form->isSubmitted(),
        ));
    }
}
