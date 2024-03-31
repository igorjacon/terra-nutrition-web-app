<?php

namespace App\Controller\Admin;

use App\Repository\ProfessionalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/management', name: 'user_management_')]
class UserManagementController extends AbstractController
{
    #[Route('/professionals', name: 'professionals')]
    public function index(ProfessionalRepository $professionalRepository): Response
    {
        $professionals = $professionalRepository->findAll();
        return $this->render('admin/professionals.html.twig', [
            'professionals' => $professionals
        ]);
    }
}
