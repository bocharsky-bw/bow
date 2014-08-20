<?php

namespace BW\UploadBundle\File;

/**
 * Class SourceImage
 * @package BW\UploadBundle\File
 */
class SourceImage extends \SplFileInfo
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int The destination image offset by X
     */
    private $offsetX = 0;

    /**
     * @var int The destination image offset by Y
     */
    private $offsetY = 0;

    /**
     * @var int The IMAGETYPE_XXX constant number
     */
    private $type;

    /**
     * @var string The HTML string of size
     */
    private $sizeForHtml;

    /**
     * @var integer The bits
     */
    private $bits;

    /**
     * @var integer The channels
     */
    private $channels;

    /**
     * @var string The MIME type
     */
    private $mimeType;

    /**
     * @var resource The image resource
     */
    private $resource;

    /**
     * @var string The image pathname relative web dir
     */
    private $webPathname;


    /**
     * The constructor
     *
     * @param $webRootDir
     * @param $webPathname
     */
    public function __construct($webRootDir, $webPathname)
    {
        $this->setWebPathname($webPathname);
        $pathname = $webRootDir . $this->getWebPathname();
        parent::__construct($pathname);
        if ( ! $this->isFile()) {
            throw new \InvalidArgumentException(sprintf(
                'File does not exist at the specified path.'
            ));
        }

        try {
            $info = getimagesize($this->getRealPath());
            if ( ! is_array($info)) {
                throw new \Exception();
            }
            $this
                ->setWidth($info[0])
                ->setHeight($info[1])
                ->setType($info[2])
                ->setSizeForHtml($info[3])
                ->setBits($info['bits'])
                ->setChannels($info['channels'])
                ->setMimeType($info['mime'])
            ;
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(sprintf(
                'File is not an image.'
            ));
        }
    }


    /**
     * Create the Image resource
     *
     * @return $this
     */
    public function createResource()
    {
        switch ($this->getType()) {
            case IMAGETYPE_JPEG: {
                $resource = imagecreatefromjpeg($this->getRealPath());

                break;
            }

            case IMAGETYPE_PNG: {
                $resource = imagecreatefrompng($this->getRealPath());

                break;
            }

            case IMAGETYPE_GIF: {
                $resource = imagecreatefromgif($this->getRealPath());

                break;
            }

            default: {
                throw new \Exception(sprintf(
                    'Undefined source image type "%d".', $this->getType()
                ));
            }
        }

        $this->setResource($resource);

        return $this;
    }


    /* SETTERS / GETTERS */

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return $this
     */
    private function setWidth($width)
    {
        $this->width = (int)$width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return $this
     */
    private function setHeight($height)
    {
        $this->height = (int)$height;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffsetX()
    {
        return $this->offsetX;
    }

    /**
     * @param int $offsetX
     */
    public function setOffsetX($offsetX)
    {
        $this->offsetX = (int)$offsetX;
    }

    /**
     * @return int
     */
    public function getOffsetY()
    {
        return $this->offsetY;
    }

    /**
     * @param int $offsetY
     */
    public function setOffsetY($offsetY)
    {
        $this->offsetY = (int)$offsetY;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    private function setType($type)
    {
        $this->type = (int)$type;

        return $this;
    }

    /**
     * @return string
     */
    public function getSizeForHtml()
    {
        return $this->sizeForHtml;
    }

    /**
     * @param string $sizeForHtml
     * @return $this
     */
    private function setSizeForHtml($sizeForHtml)
    {
        $this->sizeForHtml = (string)$sizeForHtml;

        return $this;
    }

    /**
     * @return int
     */
    public function getBits()
    {
        return $this->bits;
    }

    /**
     * @param int $bits
     * @return $this
     */
    private function setBits($bits)
    {
        $this->bits = (int)$bits;

        return $this;
    }

    /**
     * @return int
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param int $channels
     * @return $this
     */
    private function setChannels($channels)
    {
        $this->channels = (int)$channels;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return $this
     */
    private function setMimeType($mimeType)
    {
        $this->mimeType = (string)$mimeType;

        return $this;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param resource $resource
     */
    private function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return string
     */
    public function getWebPathname()
    {
        return $this->webPathname;
    }

    /**
     * @param string $webPathname
     */
    public function setWebPathname($webPathname)
    {
        $this->webPathname = (string)$webPathname;
    }
}
