<?php namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

    public function getFriendsIdsOfUser(User $user)
    {
        $array = [];

        foreach ($user->getMyFriends() as $friend) {
            $array[] = $friend->getId();
        }

        return $array;
    }
}
