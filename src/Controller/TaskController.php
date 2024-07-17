<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TaskController extends AbstractController
{
    private $taskRepository;
    private $flashMessage;

    public function __construct(TaskRepository $taskRepository,FlashBagInterface $flashMessage)
    {
        $this->taskRepository = $taskRepository;
        	$this->flashMessage = $flashMessage;
    }


    #[Route('/task', name: 'app_task')]
    public function index()
    {
       

        return $this->render('task/index.html.twig');
    }



    #[Route('/',name:'home')]
    public function home(TaskRepository $taskRepository)
    {
        $tasks=$taskRepository->findAll();
        $user = $this->getUser();
        $hello = 'Hello From Controller';
        return $this->render('task/Home.html.twig',['hello'=>$hello,'tasks'=>$tasks,'user'=>$user]);
    }

    #[Route('/task/{id}',name:"taskshow")]

    public function Show($id)
    {
        $task=$this->taskRepository->find($id);

        
        return $this->render('task/Show.html.twig',['task'=>$task]);
        
    }



    #[Route('/createtask', name: 'create_task')]


    public function CreateTask(Request $request)
    {
        $task = new Task();
        // ...

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
            $user= $this->getUser();
            $task->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            $this->flashMessage->add("success" , "Task Added !");
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('home');
        }

        return $this->render('task/Add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edittask/{id}', name: 'edit_task')]


    public function EditTask(Task $task, Request $request)
    {
        
        // ...

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            $this->flashMessage->add("success" , "Task Edited!");
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('home');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/deletetask/{id}', name: 'delete_task')]


    public function DeleteTask(Task $task)
    {
        
        // ...


        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
            $this->flashMessage->add("success" , "Task Deleted!");
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('home');
        

      
    }

}
