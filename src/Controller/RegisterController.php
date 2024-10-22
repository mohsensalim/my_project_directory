<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class RegisterController extends AbstractController
{

    private $passwordEncoder;
    private $flashMessage;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,FlashBagInterface $flashMessage)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->flashMessage = $flashMessage;
    }


    #[Route('/register', name: 'app_register')]
    public function registerUser(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        // ...

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $password_hashed = $this->passwordEncoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password_hashed);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->flashMessage->add("success" , "You Register Successfully !");
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
