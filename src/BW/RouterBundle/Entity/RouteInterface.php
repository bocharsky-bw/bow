<?php

namespace BW\RouterBundle\Entity;

interface RouteInterface
{

    /**
     * @return string
     */
    public function generatePath();

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @param Route $route
     * @return RouteInterface
     */
    public function setRoute(Route $route = null);

    /**
     * @return Route
     */
    public function getRoute();

}