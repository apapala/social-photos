<?php namespace AppBundle\Service;

use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;


class FriendManager
{
    /**
     * @var Container
     */
    private $container;

    /**
     * AccessManager constructor.
     * @param Container $container
     * @throws \Exception
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Remove user from friends
     *
     * @param User $user
     * @param User $anotherUser
     * @param bool $flush
     */
    public function removeFriend(User $currentUser, User $anotherUser, $flush = false)
    {
        $currentUser->removeMyFriend($anotherUser);
        $anotherUser->removeMyFriend($currentUser);

        $this->flush($flush);
    }

    /**
     * Add friend
     *
     * @param User $currentUser
     * @param User $anotherUser
     * @param $flush
     */
    public function addFriend(User $currentUser, User $anotherUser, $flush)
    {
        $currentUser->addMyFriend($anotherUser);

        $this->flush($flush);
    }

    /**
     * TO flash or not to flush, based on @param $flush
     * @param $flush
     */
    private function flush($flush)
    {
        if ($flush) {
            $em = $this->container->get('doctrine')->getManager();
            $em->flush();
        }
    }
}