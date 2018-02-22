<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserFriend
 *
 * @ORM\Table(name="user_friend")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserFriendRepository")
 */
class UserFriend
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="friend_id", referencedColumnName="id")
     */
    private $friend;


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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserFriend
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
     * Set friend
     *
     * @param \AppBundle\Entity\User $friend
     *
     * @return UserFriend
     */
    public function setFriend(\AppBundle\Entity\User $friend = null)
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * Get friend
     *
     * @return \AppBundle\Entity\User
     */
    public function getFriend()
    {
        return $this->friend;
    }
}
