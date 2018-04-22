<?php namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreatorVoter extends Voter {

    const CREATOR = 'CREATOR';

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
        if (!in_array($attribute, [self::CREATOR])) {
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
     * @throws \Exception
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        // Get currently logged in user
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if (method_exists($subject, 'getCreator')) {

            $subjectCreator = $subject->getCreator();

            if (!($subjectCreator instanceof User)) {
                throw new \Exception('Subject entity creator must be instance of ' . User::class);
            }

            $subject = $subjectCreator;

        } else {
            throw new \Exception('Subject entity must have getCreator() method defined');
        }

        switch ($attribute) {
            case self::CREATOR:
                return $this->checkIfCreator($subject, $user);
        }
    }

    /**
     * Checks if $subject creator id is same as current user id
     *
     * @param $subject
     * @param $user
     * @return bool
     */
    private function checkIfCreator($subject, $user)
    {

        if ($subject->getId() === $user->getId()) {
            return true;
        }

        return false;
    }
}
