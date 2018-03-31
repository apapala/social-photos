<?php namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\Exception\FriendVoterException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FriendVoter extends Voter {

    const FRIEND = 'FRIEND';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::FRIEND])) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     * @throws FriendVoterException
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Get currently logged in user
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // If $subject is instance of User, then we check whether this user is friend with currently logged in user
        if ($subject instanceof User) {

            // Check if currently logged in user is friend of provided $user
            return $this->checkIfFriends($subject, $user);
        }

        // If $subject is other entity, then we check whether currently logged in user is friend of this entitys creator.
        if (method_exists($subject, 'getCreator')) {

            $subjectCreator = $subject->getCreator();

            if (!($subjectCreator instanceof User)) {
                throw new FriendVoterException('Subject entity creator must be instance of ' . User::class);
            }

            $subject = $subjectCreator;

        } else {
            throw new FriendVoterException('Subject entity must have getCreator() method defined');
        }

        // If $subject creator is the same as logged in user then allow access
        if ($subject->getId() == $user->getId()) {
            return true;
        }

        switch ($attribute) {
            case self::FRIEND:
                return $this->checkIfFriends($subject, $user);
        }
    }

    /**
     * Check if $subject is friend with provided User and if that user is friend with a $subject
     *
     * @param $subject
     * @param $user
     * @return bool
     */
    private function checkIfFriends($subject, $user)
    {
        if ($subject->getMyFriends()->contains($user) && $user->getMyFriends()->contains($subject)) {
            return true;
        }

        return false;
    }
}
