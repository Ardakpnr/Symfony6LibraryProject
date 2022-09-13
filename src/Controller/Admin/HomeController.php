<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_home')]
    public function index(): Response
    {
        return $this->render('admin/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/admin/messages', name: 'app_admin_messages')]
    public function messages(MessageRepository $messageRepository): Response
    {
        $messagelist = $messageRepository->findAll();
        return $this->render('admin/home/messages.html.twig', [
            'messagelist' => $messagelist
        ]);
    }
    #[Route('/admin/messages/delete/{id}', name: 'app_admin_messages_delete')]
    public function messages_delete(ManagerRegistry $doctrine,$id): Response
    {
        $entityManager = $doctrine->getManager();
        $message = $entityManager->getRepository(Message::class)->find($id);
        $entityManager->remove($message);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_messages');
    }

    
    #[Route('/admin/messages/show/{id}', name: 'app_admin_messages_show')]
    public function edit(MessageRepository $messageRepository, ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $rs = $messageRepository->find($id);
        $rs->setStatus('Read');
        $entityManager->flush();
        return $this->render('admin/home/messages_show.html.twig', [
            'rs' => $rs
        ]);
    }
}