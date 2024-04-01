<?php

namespace App\Controller\Admin;

use App\Entity\Professional;
use App\Form\ProfessionalType;
use App\Repository\ProfessionalRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user/management', name: 'user_management_')]
class UserManagementController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/professionals', name: 'professionals')]
    public function professionals(ProfessionalRepository $professionalRepository): Response
    {
        $professionals = $professionalRepository->findAll();
        return $this->render('admin/professionals/index.html.twig', [
            'professionals' => $professionals,
            'title' => $this->translator->trans('ui.professionals')
        ]);
    }

    #[Route('/professional/edit/{id}', name: 'edit_professional', methods: ['GET', 'POST'])]
    public function editProfessional(Request $request, Professional $professional): Response
    {
        $form = $this->createForm(ProfessionalType::class, $professional);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_user_management_professionals');
        }

        return $this->render('admin/professionals/form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->translator->trans('ui.edit_professional')
        ]);
    }
}
