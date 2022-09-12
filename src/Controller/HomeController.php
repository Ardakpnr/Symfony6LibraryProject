<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SettingRepository $settingRepository): Response
    {
        $setting= $settingRepository->find(1);
        return $this->render('home/index.html.twig', [
            'page'=>'home',
            'setting' => $setting,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MessageRepository $messageRepository, SettingRepository $settingRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $messageRepository->add($message, true);
            $this->addFlash(
                'success',
                'Mesajınız başarı ile kayıt edilmiştir. Teşekkür ederiz'
            );
            return $this->redirectToRoute('app_contact');
        }
        $setting= $settingRepository->find(1);
        return $this->renderForm('home/contact.html.twig', [
            'form' => $form,
            'setting' => $setting
        ]);
    }
    #[Route('/aboutus', name: 'app_aboutus')]
    public function aboutus( SettingRepository $settingRepository): Response
    {
        $setting= $settingRepository->find(1);
        return $this->renderForm('home/aboutus.html.twig', [
            'setting' => $setting
        ]);
    }
    #[Route('/reference', name: 'app_reference')]
    public function refrence( SettingRepository $settingRepository): Response
    {
        $setting= $settingRepository->find(1);
        return $this->renderForm('home/reference.html.twig', [
            'setting' => $setting
        ]);
    }
    #[Route('/sss', name: 'app_sss')]
    public function sss( SettingRepository $settingRepository): Response
    {
        $setting= $settingRepository->find(1);
        return $this->renderForm('home/sss.html.twig', [
            'setting' => $setting
        ]);
    }
}
