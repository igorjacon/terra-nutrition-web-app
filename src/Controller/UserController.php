<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
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
