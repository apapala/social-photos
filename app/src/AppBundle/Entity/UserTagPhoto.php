<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserTagPhoto
 *
 * @ORM\Table(name="user_tag_photo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserTagPhotoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserTagPhoto
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    private $tag;

    /**
     * @ORM\ManyToOne(targetEntity="Photo")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    private $photo;

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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserTagPhoto
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set tag
     *
     * @param \AppBundle\Entity\Tag $tag
     *
     * @return UserTagPhoto
     */
    public function setTag(\AppBundle\Entity\Tag $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \AppBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set photo
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return UserTagPhoto
     */
    public function setPhoto(\AppBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \AppBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
