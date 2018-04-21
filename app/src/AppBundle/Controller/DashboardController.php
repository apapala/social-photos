<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserGradePhoto;
use AppBundle\Entity\UserTagPhoto;
use AppBundle\Form\UserLoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Authentication controller.
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard_index")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {

        // Show most popular tags amongs photos submitted by your friends

        $userTagPhotoRepository = $this->getDoctrine()->getRepository(UserTagPhoto::class);

        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $user = $this->getUser();

        // Get all tags from UserTagPhotoRepository where user_id is in my friends
        // No matter what photo, currently user that creates tags can do that only during creation of photo
        $tagOccurrences = $userTagPhotoRepository->findAllTagsByUsers($userRepository->getFriendsIdsOfUser($user));

        return $this->render('dashboard/index.html.twig', [
            'tagOccurrences' => $tagOccurrences
        ]);
    }
}

