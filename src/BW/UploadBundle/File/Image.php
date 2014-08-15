<?php

namespace BW\UploadBundle\File;

/**
 * Class Image
 * @package BW\UploadBundle\File
 */
class Image extends \SplFileInfo
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
     * @var int The IMAGETYPE_XXX constant number
     */
    private $type;

    /**
     * @var string
     */
    private $sizeForHtml;

    /**
     * @var integer
     */
    private $bits;

    /**
     * @var integer
     */
    private $channels;

    /**
     * @var string
     */
    private $mimeType;


    /**
     * The constructor
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        parent::__construct($filename);
        $this->init();
    }


    /**
     * Init Image object
     */
    private function init()
    {
        $info = getimagesize($this->getRealPath());
        if (is_array($info)) {
            $this
                ->setWidth($info[0])
                ->setHeight($info[1])
                ->setType($info[2])
                ->setSizeForHtml($info[3])
                ->setBits($info['bits'])
                ->setChannels($info['channels'])
                ->setMimeType($info['mime'])
            ;
        }
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

}