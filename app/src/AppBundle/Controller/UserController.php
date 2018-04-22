<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserFriend;
use AppBundle\Service\FriendManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{


    /**
     * @var FriendManager
     */
    private $friendManager;

    /**
     * UserController constructor.
     * @param FriendManager $friendManager
     */
    public function __construct(FriendManager $friendManager)
    {
        $this->friendManager = $friendManager;
    }

    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(User $user)
    {
        return $this->render('user/show.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * Makes friend with another user
     *
     * @Route("/{id}/make-friend", name="user_make_friend")
     * @Method("GET")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function makeFriendAction(User $user)
    {
        $currentUser = $this->getUser();

        $this->friendManager->makeFriend($user, $currentUser, true);

        return $this->redirectToRoute('user_index');
    }

    /**
     * Remove user from friend list and current user from users friend list
     *
     * @Route("/{id}/remove-friend", name="user_remove_friend")
     * @Method("GET")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFriendAction(User $user)
    {
        $currentUser = $this->getUser();

        $this->friendManager->removeFriend($currentUser, $user, true);

        return $this->redirectToRoute('user_index');
    }


}
