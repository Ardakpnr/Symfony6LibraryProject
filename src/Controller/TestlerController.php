<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestlerController extends AbstractController
{
    #[Route('/testler', name: 'app_testler')]
    public function index(): Response
    {
        return $this->render('testler/index.html.twig', [
            'controller_name' => 'TestlerController',
        ]);
    }
}
