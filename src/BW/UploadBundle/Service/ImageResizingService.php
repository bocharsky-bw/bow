<?php

namespace BW\UploadBundle\Service;

use BW\UploadBundle\File\DestinationImage;
use BW\UploadBundle\File\SourceImage;

/**
 * The OOP abstract layout works with JPG, PNG & GIF image types with GD library functions
 *
 * Class ImageResizingService
 * @package BW\ImageBundle\Service
 */
class ImageResizingService
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var \BW\UploadBundle\File\SourceImage The source Image file object
     */
    private $srcImage;

    /**
     * @var \BW\UploadBundle\File\DestinationImage The destination Image object
     */
    private $dstImage;


    /**
     * The constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->setRootDir(__DIR__ . '/../../../../web');
        $this->setCacheDir('/cache');
    }

    /**
     * The initialize the image objects
     *
     * @param string $webPathname
     * @return $this
     */
    public function init($webPathname)
    {
        $this->setSrcImage(new SourceImage($this->getRootDir(), $webPathname));
        $this->setDstImage(new DestinationImage());

        return $this;
    }


    /**
     * Crop the image to width and height
     *
     * @param int $width The width in pixels
     * @param int $height The height in pixels
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @TODO Maybe to add mode for cropping with scale or without scale
     */
    public function crop($width, $height)
    {
        $this->getDstImage()->setCanvasWidth($width);
        $this->getDstImage()->setCanvasHeight($height);

        if ($this->getSrcImage()->getWidth() / $this->getDstImage()->getCanvasWidth()
            > $this->getSrcImage()->getHeight() / $this->getDstImage()->getCanvasHeight()
        ) {
            // resize to height
            $factor = $this->getDstImage()->getCanvasHeight() / $this->getSrcImage()->getHeight();
            $this->getDstImage()->setWidth(
                $this->getSrcImage()->getWidth() * $factor
            );
            $this->getDstImage()->setHeight(
                $this->getSrcImage()->getHeight() * $factor
            );
            $this->getDstImage()->setOffsetX(
                ($this->getDstImage()->getCanvasWidth() - $this->getDstImage()->getWidth()) / 2
            );
        } else {
            // resize to width
            $factor = $this->getDstImage()->getCanvasWidth() / $this->getSrcImage()->getWidth();
            $this->getDstImage()->setHeight(
                $this->getSrcImage()->getHeight() * $factor
            );
            $this->getDstImage()->setWidth(
                $this->getSrcImage()->getWidth() * $factor
            );
            $this->getDstImage()->setOffsetY(
                ($this->getDstImage()->getCanvasHeight() - $this->getDstImage()->getHeight()) / 2
            );
        }

        $this->getDstImage()->createResource();
        $this->resampling();

        return $this;
    }

    /**
     * Resize the image to width and height
     *
     * @param int $width The width in pixels
     * @param int $height The height in pixels
     * @return $this
     */
    public function resize($width, $height)
    {
        $this->getDstImage()->setCanvasWidth($width);
        $this->getDstImage()->setCanvasHeight($height);

        $this->getDstImage()->setWidth($width);
        $this->getDstImage()->setHeight($height);

        $this->getDstImage()->createResource();
        $this->resampling();

        return $this;
    }

    /**
     * Resize the image to width
     *
     * @param int $width The width in pixels
     * @return $this
     */
    public function resizeToWidth($width)
    {
        $this->getDstImage()->setCanvasWidth($width);
        $divider = $this->getSrcImage()->getWidth() / $this->getSrcImage()->getHeight();
        $this->getDstImage()->setCanvasHeight(
            $this->getDstImage()->getCanvasWidth() / $divider
        );

        $this->getDstImage()->setWidth($this->getDstImage()->getCanvasWidth());
        $this->getDstImage()->setHeight($this->getDstImage()->getCanvasHeight());


        $this->getDstImage()->createResource();
        $this->resampling();

        return $this;
    }

    /**
     * Resize the image to height
     *
     * @param int $height The height in pixels
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function resizeToHeight($height)
    {
        $this->getDstImage()->setCanvasHeight($height);
        $factor = $this->getSrcImage()->getWidth() / $this->getSrcImage()->getHeight();
        $this->getDstImage()->setCanvasWidth(
            $this->getDstImage()->getCanvasHeight() * $factor
        );

        $this->getDstImage()->setWidth($this->getDstImage()->getCanvasWidth());
        $this->getDstImage()->setHeight($this->getDstImage()->getCanvasHeight());

        $this->getDstImage()->createResource();
        $this->resampling();

        return $this;
    }

    /**
     * Scale the image
     *
     * @param int|float $scale The scale in relative units
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function scale($scale)
    {
        $factor = (float)$scale;
        if (0 >= $factor) {
            throw new \InvalidArgumentException(sprintf(
                'The scale must be greater then 0, %d given.', $scale
            ));
        }
        $this->getDstImage()->setCanvasWidth(
            $this->getSrcImage()->getWidth() * $factor
        );
        $this->getDstImage()->setCanvasHeight(
            $this->getSrcImage()->getHeight() * $factor
        );

        $this->getDstImage()->setWidth($this->getDstImage()->getCanvasWidth());
        $this->getDstImage()->setHeight($this->getDstImage()->getCanvasHeight());

        $this->getDstImage()->createResource();
        $this->resampling();

        return $this;
    }

    /**
     * The Image resampling
     *
     * @return bool
     * @throws \Exception
     */
    private function resampling()
    {
        $success = imagecopyresampled(
            (null !== $this->getDstImage()->getResource()
                ? $this->getDstImage()->getResource()
                : $this->getDstImage()->createResource()->getResource()
            ),
            (null !== $this->getSrcImage()->getResource()
                ? $this->getSrcImage()->getResource()
                : $this->getSrcImage()->createResource()->getResource()
            ),
            $this->getDstImage()->getOffsetX(),
            $this->getDstImage()->getOffsetY(),
            $this->getSrcImage()->getOffsetX(),
            $this->getSrcImage()->getOffsetY(),
            $this->getDstImage()->getWidth(),
            $this->getDstImage()->getHeight(),
            $this->getSrcImage()->getWidth(),
            $this->getSrcImage()->getHeight()
        );

        if ( ! $success) {
            throw new \RuntimeException('The destination image resampling failed.');
        }

        return $success;
    }

    /**
     * Save the Image to the host
     *
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
//        $this->getDstImage()->setFilename(
//            $this->getSrcImage()->getFilename()
//        );
        $this->getDstImage()->setPathname(''
            . $this->getRootDir()
            . $this->getCacheDir()
            . DIRECTORY_SEPARATOR
            . $this->getDstImage()->getCanvasWidth()
            . 'x'
            . $this->getDstImage()->getCanvasHeight()
            . $this->getSrcImage()->getWebPathname()
        );
        if ( ! is_dir($this->getDstImage()->getPath())) {
            // Create not exists folders recursively
            if ( ! mkdir($this->getDstImage()->getPath(), 0755, true)) {
                throw new \Exception(sprintf(
                    'Could not create a folder "%s"', $this->getDstImage()->getPath()
                ));
            }
        }

        switch ($this->getSrcImage()->getType()) {
            case IMAGETYPE_JPEG: {
                $success = imagejpeg(
                    $this->getDstImage()->getResource(),
                    $this->getDstImage()->getPathname(),
                    $this->getDstImage()->getQuality()
                );

                break;
            }

            case IMAGETYPE_PNG: {
                $success = imagepng(
                    $this->getDstImage()->getResource(),
                    $this->getDstImage()->getPathname(),
                    $this->getDstImage()->getQuality()
                );

                break;
            }

            case IMAGETYPE_GIF: {
                $success = imagegif(
                    $this->getDstImage()->getResource(),
                    $this->getDstImage()->getPathname()
                );

                break;
            }

            default: {
                throw new \Exception(sprintf(
                    'Undefined source image type "%d".', $this->getSrcImage()->getType()
                ));
            }
        }

        return $success;
    }

    public function thumbnailExists($width, $height)
    {

        return file_exists($this->getRootDir() . $this->generateThumbnailWebPathname($width, $height));
    }

    public function generateThumbnailWebPathname($width, $height)
    {
        return $this->getCacheDir() . DIRECTORY_SEPARATOR . $width . 'x' . $height . $this->getSrcImage()->getWebPathname();
    }


    /* SETTERS / GETTERS */

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param string $rootDir
     */
    private function setRootDir($rootDir)
    {
        $this->rootDir = (string)$rootDir;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * @param string $cacheDir
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = (string)$cacheDir;
    }

    /**
     * @return SourceImage
     */
    public function getSrcImage()
    {
        return $this->srcImage;
    }

    /**
     * @param SourceImage $srcImage
     */
    public function setSrcImage(SourceImage $srcImage)
    {
        $this->srcImage = $srcImage;
    }

    /**
     * @return DestinationImage
     */
    public function getDstImage()
    {
        return $this->dstImage;
    }

    /**
     * @param DestinationImage $dstImage
     */
    public function setDstImage(DestinationImage $dstImage)
    {
        $this->dstImage = $dstImage;
    }
}