<?php

namespace BW\UploadBundle\Service;

use BW\UploadBundle\File\Image;

/**
 * The OOP abstract layout works with JPG, PNG & GIF image types with GD library functions
 *
 * Class ImageResizingService
 * @package BW\ImageBundle\Service
 */
class ImageResizingService
{
    /**
     * @var Image
     */
    private $image;


    /**
     * The constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
    }


    /**
     * The initialize Image object
     *
     * @param Image $image
     */
    public function init(Image $image)
    {
        $this->image = $image;
    }

    /**
     * @param int $width The width in pixels
     * @param int $height The height in pixels
     */
    public function resize($width, $height)
    {
        $width = (int)$width;
        $height = (int)$height;
        if (0 >= $width || 0 >= $height) {
            throw new \InvalidArgumentException(sprintf(
                'The width/height must be greater then 0, %d/%d given.', $width, $height
            ));
        }
        $this->process($width, $height);
    }

    /**
     * @param int $width The width in pixels
     */
    public function resizeToWidth($width)
    {
        $width = (int)$width;
        if (0 >= $width) {
            throw new \InvalidArgumentException(sprintf(
                'The width must be greater then 0, %d given.', $width
            ));
        }
        $divider = $this->image->getWidth() / $this->image->getHeight();
        $height = (int)($width / $divider);
        $height = 0 < $height ? $height : 0;

        $this->process($width, $height);
    }

    /**
     * @param int $height The height in pixels
     * @throws \InvalidArgumentException
     */
    public function resizeToHeight($height)
    {
        $height = (int)$height;
        if (0 >= $height) {
            throw new \InvalidArgumentException(sprintf(
                'The height must be greater then 0, %d given.', $height
            ));
        }
        $factor = $this->image->getWidth() / $this->image->getHeight();
        $width = (int)($height * $factor);
        $width = 0 < $width ? $width : 0;

        $this->process($width, $height);
    }

    /**
     * Crop the image
     *
     * @param int $width The width in pixels
     * @param int $height The height in pixels
     * @throws \InvalidArgumentException
     * @throws \Exception
     * @TODO Maybe to add mode for cropping with scale or without scale
     */
    public function crop($width, $height)
    {
        $dstQuality = 75;
        $dstOffsetX = 0;
        $dstOffsetY = 0;

        $width = (int)$width;
        $height = (int)$height;
        if (0 >= $width || 0 >= $height) {
            throw new \InvalidArgumentException(sprintf(
                'The width/height must be greater then 0, %d/%d given.', $width, $height
            ));
        }

        if ($this->image->getWidth()/$width > $this->image->getHeight()/$height) {
            // resize to height
            $factor = $height / $this->image->getHeight();
            $dstWidth = (int)($this->image->getWidth() * $factor);
            $dstHeight = (int)($this->image->getHeight() * $factor);
            $dstOffsetX = (int)(($width - $dstWidth) / 2);
        } else {
            // resize to width
            $factor = $width / $this->image->getWidth();
            $dstHeight = (int)($this->image->getHeight() * $factor);
            $dstWidth = (int)($this->image->getWidth() * $factor);
            $dstOffsetY = (int)(($height - $dstHeight) / 2);
        }

        $srcResource = imagecreatefromjpeg($this->image->getRealPath()); // The source resource
        $dstResource = imagecreatetruecolor($width, $height); // The destination resource

        imagecopyresampled($dstResource, $srcResource, $dstOffsetX, $dstOffsetY, 0, 0, $dstWidth, $dstHeight, $this->image->getWidth(), $this->image->getHeight());

        $dstFilename = $this->image->getFilename() . '_';
        $dstPath = $this->image->getPath();
        if ( ! is_dir($dstPath)) {
            // Создание необходимых дирекотрий перед сохранением, если их нет
            if ( ! mkdir($dstPath, 0755, true)) {
                throw new \Exception(sprintf(
                    'could not create a folder "%s"', $dstPath
                ));
            }
        }
        $dstPathname = $dstPath . DIRECTORY_SEPARATOR . $dstFilename;

        imagejpeg($dstResource, $dstPathname, $dstQuality); // создание JPG изображения и его сохранение в целевой директории
    }

    /**
     * @param int|float $scale The scale in em
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

        $width = (int)($this->image->getWidth() * $factor);
        $height = (int)($this->image->getHeight() * $factor);

        $this->process($width, $height);
    }

    /**
     * @param int $width
     * @param int $height
     * @throws \Exception
     */
    private function process($width, $height)
    {
        $dstQuality = 75;
        $srcResource = imagecreatefromjpeg($this->image->getRealPath()); // Ресурс исходного JPG изображения
        $dstResource = imagecreatetruecolor($width, $height); // Ресурс целевого изображения

        imagecopyresampled($dstResource, $srcResource, 0, 0, 0, 0, $width, $height, $this->image->getWidth(), $this->image->getHeight());

        $dstFilename = $this->image->getFilename() . '_';
        $dstPath = $this->image->getPath();
        if ( ! is_dir($dstPath)) {
            // Создание необходимых дирекотрий перед сохранением, если их нет
            if ( ! mkdir($dstPath, 0755, true)) {
                throw new \Exception(sprintf(
                    'could not create a folder "%s"', $dstPath
                ));
            }
        }
        $dstPathname = $dstPath . DIRECTORY_SEPARATOR . $dstFilename;

        imagejpeg($dstResource, $dstPathname, $dstQuality); // создание JPG изображения и его сохранение в целевой директории
    }
}