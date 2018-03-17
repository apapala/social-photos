<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, unique=false, nullable=true)
     */
    private $filename = null;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, unique=false, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail", type="string", length=255, unique=false, nullable=true)
     */
    private $thumbnail;

    /**
     * One photo has many userTagPhoto.
     * @ORM\OneToMany(targetEntity="UserTagPhoto", mappedBy="photo")
     */
    private $userTagPhotos;

    /**
     * One Photo has many userGradePhoto.
     * @ORM\OneToMany(targetEntity="UserGradePhoto", mappedBy="photo")
     */
    protected $userGradePhotos;

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="photo")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $creator;

    public function __construct()
    {
        $this->userTagPhotos = new ArrayCollection();
        $this->userGradePhotos = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return Photo
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

    public function getUserTagPhotos()
    {
        return $this->userTagPhotos;
    }

    public function setUserTagPhotos($userTagPhotos)
    {
        $this->userTagPhotos = $userTagPhotos;
    }

    /**
     * @return mixed
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param mixed $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return mixed
     */
    public function getUserGradePhotos()
    {
        return $this->userGradePhotos;
    }

    /**
     * @param string $image
     * @return Photo
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $thumbnail
     * @return Photo
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }
}
