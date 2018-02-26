<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Grade;
use AppBundle\Entity\Photo;
use AppBundle\Entity\Tag;
use AppBundle\Entity\UserGradePhoto;
use AppBundle\Entity\UserTagPhoto;
use AppBundle\Form\PhotoType;
use AppBundle\Form\UserGradePhotoType;
use AppBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Photo controller.
 *
 * @Route("photo")
 */
class PhotoController extends Controller
{
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
     */
    public function createAction(Request $request, FileUploader $fileUploader)
    {
        $photo = new Photo();

        $form = $this->createForm(PhotoType::class, $photo, [
            'action' => $this->generateUrl('photo_create'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // Check if $request has new file
            if ($request->files->get('photo')['filename']) {
                $file = $fileUploader->upload($photo->getFilename(), $this->getParameter('photos_directory'));
                $photo->setFilename($file);
            } else {
                $photo->setFilename('');
            }

            // Set creator of this photo
            $photo->setCreator($this->getUser());

            $em->persist($photo);

            // Save tags
            if (!empty($form->get('tags')->getData())) {

                $photoRepository = $this->getDoctrine()->getRepository(Photo::class);
                $photoRepository->addTagsToPhoto(explode(',', $form->get('tags')->getData()), $photo, $this->getUser());

            }

            $em->flush();

            return $this->redirectToRoute('photo_index');

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
     * @Route("/{photo}", name="photo_show", requirements={"photo"="\d+"})
     * @Method("GET")
     */
    public function showAction(Photo $photo)
    {
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
