<?php

namespace BW\RouterBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class DatabaseRouteLoadingEventListener
 * @package BW\RouterBundle\EventListener
 */
class DatabaseRouteLoadingEventListener
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * @var \BW\RouterBundle\Entity\Route
     */
    private $currentRoute;


    /**
     * The constructor
     *
     * @param RequestStack $requestStack
     * @param EntityManager $em
     * @param Router $router
     */
    public function __construct(RequestStack $requestStack, EntityManager $em, Router $router, Logger $logger)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->em = $em;
        $this->router = $router;
        $this->logger = $logger;
        $this->logger->debug(sprintf(
            'Loaded event listener "%s".',
            __METHOD__
        ));
    }


    /**
     * Get current route object
     *
     * @return \BW\RouterBundle\Entity\Route
     */
    public function getCurrentRoute()
    {
        return $this->currentRoute;
    }

    /**
     * Search route match in database
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
//        die('onKernelRequest');
        $this->logger->debug(sprintf(
            'Notified event "%s" to listener "%s".',
            $event->getName(),
            __METHOD__
        ));

        if ( ! $event->isMasterRequest()) {
            // don't do anything if it's not the master request
            $this->logger->debug(sprintf(
                'It is not master request. Abort search in database.'
            ));
            return;
        }

        if ( ! $this->request->isMethod('GET')) {
            // don't do anything if request method isn't GET
            $this->logger->debug(sprintf(
                'It is "%s" request method, "GET" expected. Abort search in database.',
                $this->request->getMethod()
            ));
            return;
        }

        if ($this->isRouteMatched()) {
            // don't do anything if route already is matched
            $this->logger->debug(sprintf(
                'Static route already matched. Abort search in database.'
            ));
            return;
        }

        $this->logger->debug(sprintf(
            'Search route with path "%s" in database.',
            $this->request->getPathInfo()
        ));

        /** @var \BW\RouterBundle\Entity\Route $route */
        $route = $this->em->getRepository('BWRouterBundle:Route')->findOneBy(array(
            'path' => $this->request->getPathInfo(),
        ));

        if ( ! $route) {
            // don't do anything if route not found in database
            $this->logger->error(sprintf(
                'Route with path "%s" not found in database.',
                $this->request->getPathInfo()
            ));
            return;
        }
        $this->logger->debug(sprintf(
            'Route with path "%s" matched in database.',
            $this->request->getPathInfo()
        ));

        $this->currentRoute = $route;
        $this->request->attributes->replace($route->getDefaults());
    }

    /**
     * Whether is route matched
     *
     * @return bool
     */
    private function isRouteMatched()
    {
        try {
            $this->router->matchRequest($this->request);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
