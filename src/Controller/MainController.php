<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/privacy-policy', name: 'privacy_policy', methods: ['GET'])]
    public function viewPrivacyPolicy(): Response
    {
        return $this->render('privacy_policy.html.twig');
    }
}
