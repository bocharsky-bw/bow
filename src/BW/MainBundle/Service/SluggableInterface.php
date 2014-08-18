<?php

namespace BW\MainBundle\Service;

/**
 * Interface SluggableInterface
 * @package BW\MainBundle\Service
 */
interface SluggableInterface
{
    /**
     * @return string
     */
    public function getStringForSlug();

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function getSlug();
}