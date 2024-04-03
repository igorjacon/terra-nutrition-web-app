<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResettingType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Utils\TokenGenerator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/resetting', name: 'resetting_')]
class RequestController extends AbstractController
{
    private TranslatorInterface $translator;
    private ManagerRegistry $doctrine;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine)
    {
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }

    #[Route('/request', name: 'request')]
    public function request(): Response
    {
        return $this->render('resetting/request.html.twig');
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param Mailer $mailer
     * @return Response
     */
    #[Route('/send', name: 'send')]
    public function send(Request $request, UserRepository $userRepository, Mailer $mailer): Response
    {
        $email = $request->request->get('email');
        $username = $request->request->get('username');
        $user = $userRepository->findOneByUsername($email);

        if ($user) {
            $user->setPasswordRequestedAt(new \DateTime());
            $user->setConfirmationToken(TokenGenerator::generateToken());

            $em = $this->doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $mailer->sendEmail(
                'email.resetting.subject',
                null,
                $user->getEmail(),
                'email/resetting.html.twig',
                [
                    'url' => $this->generateUrl('resetting_reset', ['confirmationToken' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL),
                    'subject' => $this->translator->trans('email.resetting.subject'),
                    'message' => $this->translator->trans('email.resetting.message'),
                    'username' => $user->getUsername(),
                ]
            );
        }

        return $this->render('resetting/request_success.html.twig');
    }

    /**
     * @param Request $request
     * @param User $user
     * @param string $confirmationToken
     * @param UserPasswordHasher $hasher
     * @return Response
     */
    #[Route('/reset/{confirmationToken}', name: 'reset')]
    public function reset(Request $request, string $confirmationToken, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {
        $user = $userRepository->findOneBy(['confirmationToken' => $confirmationToken]);

        if ((time() - $user->getPasswordRequestedAt()->getTimestamp()) > 86400) {

            $this->addFlash('error', $this->translator->trans('ui.resetting.request_expired'));

            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(ResettingType::class, $user, [
            'action' => $this->generateUrl('resetting_reset', [
                'confirmationToken' => $confirmationToken,
            ])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            $user->setConfirmationToken(null);
            $user->setPasswordRequestedAt(null);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('ui.resetting.reset_valid'));

            return $this->redirectToRoute('homepage');
        }

        return $this->render('resetting/reset.html.twig', array(
            'confirmationToken' => $confirmationToken,
            'form' => $form->createView(),
        ));
    }
}
