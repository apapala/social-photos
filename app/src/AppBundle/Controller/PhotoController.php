<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grade;
use AppBundle\Entity\Photo;
use AppBundle\Entity\UserGradePhoto;
use AppBundle\Entity\UserTagPhoto;
use AppBundle\Form\PhotoType;
use AppBundle\Form\UserGradePhotoType;
use AppBundle\Service\AccessManager;
use AppBundle\Service\FileManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Photo controller.
 *
 * @Route("photo")
 */
class PhotoController extends Controller
{
    /**
     * @var AccessManager
     */
    private $accessManager;

    /**
     * PhotoController constructor.
     * @param AccessManager $accessManager
     */
    public function __construct(AccessManager $accessManager)
    {
        $this->accessManager = $accessManager;
    }

    /**
     * Lists all photo entities.
     *
     * @Route("/", name="photo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $photos = $em->getRepository('AppBundle:Photo')->findAll();

        return $this->render('photo/index.html.twig', array(
            'photos' => $photos,
        ));
    }

    /**
     * Finds and displays a photo entity.
     *
     * @Route("/create", name="photo_create")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request, FileManager $fileManager)
    {
        $photo = new Photo();

        $form = $this->createForm(PhotoType::class, $photo, [
            'action' => $this->generateUrl('photo_create'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $fileManager
                ->setFormFields(['filename', 'image', 'thumbnail'])
                ->setPath($this->getParameter('photos_directory'))
                ->setBlockPrefix('photo');

            $photo = $fileManager->uploadRequestFile($request, $photo);

            $photo->setUserTagPhotos(new ArrayCollection()); // Because form type is mapped and tags handled by repository?

            // Set creator of this photo
            $photo->setCreator($this->getUser());

            $em->persist($photo);

            // Save tags
            if (!empty($form->get('userTagPhotos')->getData())) {

                $userTagPhotoRepository = $this->getDoctrine()->getRepository(UserTagPhoto::class);
                $userTagPhotoRepository->addTagsToPhoto(explode(',', $form->get('userTagPhotos')->getData()), $photo, $this->getUser());

            }
            
            $em->flush();

            $this->accessManager->setAsOwner($photo);

            return $this->redirectToRoute('photo_edit', ['photo' => $photo->getId()]);

//            // More redirection options
//
//            // redirect to the "homepage" route
//            return $this->redirectToRoute('homepage');
//
//            // do a permanent - 301 redirect
//            return $this->redirectToRoute('homepage', array(), 301);
//
//            // redirect to a route with parameters
//            return $this->redirectToRoute('blog_show', array('slug' => 'my-page'));
//
//            // redirect externally
//            return $this->redirect('http://symfony.com/doc');

        }

        return $this->render('photo/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a photo entity.
     *
     * @Route("/{photo}/edit", name="photo_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param FileManager $fileManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Photo $photo, Request $request, FileManager $fileManager)
    {
        if ($this->isGranted('EDIT', $photo) == false) {
            return $this->redirectToRoute('photo_index');
        }

        $form = $this->createForm(PhotoType::class, $photo, [
            'action' => $this->generateUrl('photo_edit', ['photo' => $photo->getId()]),
            'method' => 'POST',
            'validation_groups' => 'edit'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $fileManager
                ->setFormFields(['filename'])
                ->setPath($this->getParameter('photos_directory'))
                ->setBlockPrefix('photo');

            $photo = $fileManager->uploadRequestFile($request, $photo);

            $fileManager->setFormFields(['image', 'thumbnail']);

            $photo = $fileManager->uploadRequestFile($request, $photo);

            $photo->setUserTagPhotos(new ArrayCollection()); // Because form type is mapped and tags handled by repository?

            // Set creator of this photo
            $photo->setCreator($this->getUser());

            $em->persist($photo);

            // Save tags
            if (!empty($form->get('userTagPhotos')->getData())) {

                $userTagPhotoRepository = $this->getDoctrine()->getRepository(UserTagPhoto::class);
                $userTagPhotoRepository->removeTagsOnPhoto($photo, $this->getUser());
                $userTagPhotoRepository->addTagsToPhoto(explode(',', $form->get('userTagPhotos')->getData()), $photo, $this->getUser());

            }

            $em->flush();

            return $this->redirectToRoute('photo_edit', ['photo' => $photo->getId()]);

//            // More redirection options
//
//            // redirect to the "homepage" route
//            return $this->redirectToRoute('homepage');
//
//            // do a permanent - 301 redirect
//            return $this->redirectToRoute('homepage', array(), 301);
//
//            // redirect to a route with parameters
//            return $this->redirectToRoute('blog_show', array('slug' => 'my-page'));
//
//            // redirect externally
//            return $this->redirect('http://symfony.com/doc');

        }

        // $photo->setFilename($photo->getFilename());

        return $this->render('photo/edit.html.twig', array(
            'form' => $form->createView(),
            'photo' => $photo
        ));
    }

    /**
     * Finds and displays a photo entity.
     *
     * @Route("/{photo}", name="photo_show", requirements={"photo"="\d+"})
     * @Method("GET")
     */
    public function showAction(Photo $photo)
    {
        // Check if logged in user is friend with creator of $photo
        if ($this->isGranted('friend', $photo) == false) {

            $this->addFlash('notice', "You need to be a friend");

            return $this->redirectToRoute('photo_index');
        }

        $userGradePhotoRepository = $this->getDoctrine()->getRepository(UserGradePhoto::class);

        $userGradePhoto = new UserGradePhoto();
        $userGradePhotoForm = $this->createForm(UserGradePhotoType::class, $userGradePhoto, [
            'action' => $this->generateUrl('photo_grade', ['photo' => $photo->getId()]),
            'method' => 'POST',
        ]);

        $averageGrade = $userGradePhotoRepository->getAverageGradeOfPhoto($photo);
        $numberOfGradesOfPhoto = $userGradePhotoRepository->getNumberOfGradesOfPhoto($photo);

        $userGradePhoto = $userGradePhotoRepository->findOneByPhotoAndUser($photo, $this->getUser());

        return $this->render('photo/show.html.twig', array(
            'photo' => $photo,
            'userGradePhotoForm' => $userGradePhotoForm->createView(),
            'userGradePhoto' => $userGradePhoto,
            'averageGrade' => $averageGrade,
            'numberOfGradesOfPhoto' => $numberOfGradesOfPhoto
        ));
    }

    /**
     * Finds and displays a photo entity.
     *
     * @Route("/{photo}/grade", name="photo_grade", requirements={"photo"="\d+"})
     * @Method("POST")
     */
    public function gradePhotoAction(Request $request, Photo $photo)
    {
        $userGradePhotoRepository = $this->getDoctrine()->getRepository(UserGradePhoto::class);

        // Check if user already rated this photo
        if (!$userGradePhotoRepository->findOneByPhotoAndUser($photo, $this->getUser())) {
            $userGradePhoto = new UserGradePhoto();
            $userGradePhotoForm = $this->createForm(UserGradePhotoType::class, $userGradePhoto, [
                'action' => $this->generateUrl('photo_grade', ['photo' => $photo->getId()]),
                'method' => 'POST',
            ]);

            $userGradePhotoForm->handleRequest($request);

            if ($userGradePhotoForm->isSubmitted() && $userGradePhotoForm->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $gradeRepository = $this->getDoctrine()->getRepository(Grade::class);
                $grade = $gradeRepository->findOneBy(['grade' => $userGradePhotoForm->get('grade')->getData()]);

                $userGradePhoto->setUser($this->getUser());
                $userGradePhoto->setGrade($grade);
                $userGradePhoto->setPhoto($photo);

                $em->persist($userGradePhoto);
                $em->flush();

                $this->redirectToRoute('photo_show', array('photo' => $photo->getId()));

            }
        } else {
            // User have rated this photo already
        }

        return $this->redirectToRoute('photo_show', array('photo' => $photo->getId()));

    }

}
