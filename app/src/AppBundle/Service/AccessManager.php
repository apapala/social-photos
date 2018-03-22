<?php namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessManager
{
    /**
     * @var Container
     */
    private $container;

    private $aclProvider;

    /**
     * AccessManager constructor.
     * @param Container $container
     * @throws \Exception
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->aclProvider = $container->get('security.acl.provider');
    }

    public function setAsOwner($entity, $user = null)
    {
        // Create ACL
        $objectIdentity = ObjectIdentity::fromDomainObject($entity);

        $acl = $this->aclProvider->createAcl($objectIdentity);

        if (!$user) {
            $user = $this->getUser();
        }

        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // Grant owner access
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);

        // Automatically add EDIT and DELETE
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_EDIT);
        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_DELETE);

        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Get currently logged in user
     *
     * @return mixed
     * @throws \Exception
     */
    public function getUser()
    {
        // Retrieving the security identity of the currently logged-in user
        $tokenStorage = $this->container->get('security.token_storage');

        $user = $tokenStorage->getToken()->getUser();

        return $user;
    }
}