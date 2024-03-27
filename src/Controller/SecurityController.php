<?php

namespace App\Controller;

use App\Service\Mailer;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Amount of times, the user can request the authcode in a session or x+1hours
     */
    public const TWOFA_AUTHCODE_MAX = 10;
    /**
     * Amount of Hours, the user need to wait until the request-counter is reset
     */
    public const TWOFA_AUTHCODE_HU_REST = 5;

    #[Route('/login', name: 'security_login')]
    public function login(AuthenticationUtils $helper, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        if (true === $authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') OR true === $authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('security/login.html.twig', [
            'last_id' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * This is the route the user use to request an email with the authCode
     *
     * @param Mailer $mailer
     * @return Response
     */
    #[Route('/2fa/resend/authCode', name: 'security_authCode')]
    public function resendAuthCode(Mailer $mailer, Request $request): Response
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $attempt = $session->get('2fa_auth_code_attempts');
        $last_attempt_timestamp = $session->get('2fa_auth_code_timestamp');
        if(empty($attempt))$attempt = 0;

        if(!empty($last_attempt_timestamp) and
            $last_attempt_timestamp->diff(new DateTime('now'))->h>SecurityController::TWOFA_AUTHCODE_HU_REST
        ) {
            $attempt = 0;
        }

        if($attempt < SecurityController::TWOFA_AUTHCODE_MAX) {
            $mailer->sendAuthCode($user);
            $session->set('2fa_auth_code_attempts', $attempt + 1);
        }
        else{
            $session->set('2fa_auth_code_timestamp',new DateTime('now'));
        }

        return $this->redirectToRoute('2fa_login');
    }

    /**
     * This is the route the user can use to logout.
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in config/packages/security.yaml
     */
    #[Route('/logout', name: 'security_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
