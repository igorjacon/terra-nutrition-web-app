<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccessPortalChecker implements UserCheckerInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getCustomer()) {
            throw new CustomUserMessageAuthenticationException(
                $this->translator->trans('ui.user.unauthorized', [], 'messages')
            );
        }

        if ($user->hasRole(User::ROLES_ALLOWED[User::ROLE_CUSTOMER])) {
            throw new CustomUserMessageAuthenticationException('Access Denied');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getCustomer()) {
            throw new CustomUserMessageAuthenticationException(
                $this->translator->trans('ui.user.unauthorized', [], 'messages')
            );
        }
    }
}