<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('profile/index.html.twig', [
            'user' =>  $this->getUser() ,
        ]);
    }



    #[Route('/update/image', name: 'upload_image')]
    public function updateProfileImage(Request $request,FlashBagInterface $flashMessage)
    {
       
       $image = $request->files->get("image");
      
       if ($image) {
        $image_name = $image->getClientOriginalName();
       $image_name = $image->getClientOriginalName();
       $image->move($this->getParameter('image_directory'),$image_name);
       $user = $this->getUser();
       $user->setImage($image_name);
       $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($user);
       $entityManager->flush();
       $flashMessage->add("success" , "You Profile Modified Successfully !");
       // ... perform some action, such as saving the task to the database

       return $this->redirectToRoute('app_profile');
    }
    else{
        $flashMessage->add("error" , "Error");
        return $this->redirectToRoute('app_profile');
    }
}
}
