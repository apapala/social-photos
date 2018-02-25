<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class RegistrationController
 *
 * @package AppBundle\Controller
 */
class RegistrationController extends Controller
{

    /**
     * @Route("/register", name="register")
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('register'),
            'method' => 'POST',
            'validation_groups' => 'withPassword'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $validator = $this->get('validator');
            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                /*
                 * Uses a __toString method on the $errors variable which is a
                 * ConstraintViolationList object. This gives us a nice string
                 * for debugging.
                 */
                $errorsString = (string) $errors;

                return new Response($errorsString);
            } else {
                // die('asd');
            }

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('register_success');

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

        return $this->render('security/register.html.twig', array(
            'formRegister' => $form->createView()
        ));

    }

    /**
     * @Route("/register/success", name="register_success")
     */
    public function registerSuccessAction()
    {
        return $this->render('security/register_success.html.twig',[]);
    }

}
