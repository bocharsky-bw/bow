<?php

namespace BW\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Image
 * @package BW\BlogBundle\Entity
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="BW\BlogBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string The title attr
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $title = '';

    /**
     * @var string The alt attr
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $alt = '';

    /**
     * @var string The path
     *
     * @ORM\Column(name="sub_folder", type="string", length=255, nullable=true)
     */
    protected $subFolder = '';

    /**
     * @var null|string The path
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $filename = null;

    /**
     * @var null|string The temp path of image
     */
    protected $temp = null;

    /**
     * @var UploadedFile The uploaded file
     *
     * @Assert\File(maxSize="6000000")
     */
    protected $file;


    public function getAbsolutePath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadRootDir() . '/' . $this->filename;
    }

    public function getWebPath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadDir() . '/' . $this->filename;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    /**
     * get rid of the __DIR__ so it does not screw up
     * when displaying uploaded doc/image in the view.
     *
     * @return string 'uploads/path/to/subfolder'
     */
    protected function getUploadDir() {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads' . ($this->subFolder ? '/' . $this->subFolder : '');
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->filename)) {
            // store the old name to delete after the update
            $this->temp = $this->filename;
            $this->filename = null;
        } else {
            $this->filename = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->filename = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->filename);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set subFolder
     *
     * @param string $subFolder
     * @return Image
     */
    public function setSubFolder($subFolder)
    {
        $this->subFolder = str_replace('../', '', $subFolder);

        return $this;
    }


    public function __construct()
    {
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Image
     */
    public function setTitle($title)
    {
        if(isset($title)) {
            $this->title = $title;
        } else {
            $this->title = '';
        }

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        if(isset($alt)) {
            $this->alt = $alt;
        } else {
            $this->alt = '';
        }

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Get subFolder
     *
     * @return string 
     */
    public function getSubFolder()
    {
        return $this->subFolder;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
