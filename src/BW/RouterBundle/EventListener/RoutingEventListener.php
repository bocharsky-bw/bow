<?php

namespace BW\RouterBundle\EventListener;

use BW\RouterBundle\Entity\Route;
use BW\RouterBundle\Entity\RouteInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class RoutingEventListener
 * @package BW\RouterBundle\EventListener
 */
class RoutingEventListener
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;


    /**
     * The constructor
     *
     * @param RequestStack $requestStack
     * @param Router $router
     * @param Logger $logger
     */
    public function __construct(RequestStack $requestStack, Router $router, Logger $logger)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
        $this->logger = $logger;
        $this->logger->debug(sprintf(
            'Loaded event listener "%s".',
            __METHOD__
        ));
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $this->handleEntity($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->handleEntity($args);
    }

    private function handleEntity(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof RouteInterface) {
            if (null === $entity->getRoute()) {
                $entity->setRoute(new Route());
            }

            $entity->getRoute()->handleEntity($entity);

            $em->flush();
        }
    }
}
