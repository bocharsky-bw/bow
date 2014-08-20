<?php

namespace BW\UploadBundle\Twig;

use BW\UploadBundle\Service\ImageResizingService;
use Symfony\Bridge\Monolog\Logger;

class BWExtension extends \Twig_Extension
{

    /**
     * @var \BW\UploadBundle\Service\ImageResizingService The ImageResizingService instance
     */
    protected $resizer;

    /**
     * @var \Symfony\Bridge\Monolog\Logger The logger instance
     */
    private $logger;


    /**
     * The constructor
     *
     * @param ImageResizingService $resizer
     * @param Logger $logger
     */
    public function __construct(ImageResizingService $resizer, Logger $logger)
    {
        $this->resizer = $resizer;
        $this->logger = $logger;
        $this->logger->debug(sprintf(
            'Loaded twig extension "%s".',
            __METHOD__
        ));
    }


    public function getName()
    {
        return 'bw_upload_extension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('resize', array($this, 'resizeFilter')),
            new \Twig_SimpleFilter('crop', array($this, 'cropFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('resize', array($this, 'resizeFunction')),
            new \Twig_SimpleFunction('crop', array($this, 'cropFunction')),
        );
    }


    public function resizeFilter($webPathname, $width, $height)
    {
        return $this->resize($webPathname, $width, $height);
    }

    public function resizeFunction($webPathname, $width, $height)
    {
        return $this->resize($webPathname, $width, $height);
    }

    private function resize($webPathname, $width, $height)
    {
        $this->resizer->init($webPathname);

        if ( ! $this->resizer->thumbnailExists($width, $height)) {
            $this
                ->resizer
                ->resize($width, $height)
                ->save()
            ;
        }

        return $this->resizer->generateThumbnailWebPathname($width, $height);
    }

    public function cropFilter($webPathname, $width, $height)
    {
        return $this->crop($webPathname, $width, $height);
    }

    public function cropFunction($webPathname, $width, $height)
    {
        return $this->crop($webPathname, $width, $height);
    }

    private function crop($webPathname, $width, $height)
    {
        $this->resizer->init($webPathname);

        if ( ! $this->resizer->thumbnailExists($width, $height)) {
            $this
                ->resizer
                ->crop($width, $height)
                ->save()
            ;
        }

        return $this->resizer->generateThumbnailWebPathname($width, $height);
    }
}