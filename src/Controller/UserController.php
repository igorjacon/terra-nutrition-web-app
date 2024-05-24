<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/settings', name: 'settings', methods: ['GET', 'POST'])]
    public function settings(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('enabled');
        $form->remove('roles');
        $form->remove('authCode');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('user_settings');
        }

        return $this->render('admin/user/settings.html.twig', [
            'form' => $form->createView(),
            'title' => $user,
            'user' => $user
        ]);
    }
    #[Route('/locale', name: 'locale', methods: ['GET', 'POST'])]
    public function setLocale(Request $request): Response
    {
        $user = $this->getUser();
        $locale = $request->get('code', 'en');
//        if(null !== $user->getLocale()) {
//        }
        $request->getSession()->set('_locale', strtolower($locale));

        return $this->redirect($request->headers->get('referer'));
    }
}
