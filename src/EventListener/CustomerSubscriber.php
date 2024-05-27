<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    public function onKernelRequest(RequestEvent $event)
    {
        if ($user = $this->getUser()) {
            $filter = $this->em->getFilters()->enable('customer_filter');
            $filter->setParameter('customer_id', $user->getId());
        }
    }

    private function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }
        $user = $token->getUser();

        if (!($user instanceof UserInterface)) {
            return null;
        }
        if (!$user->getCustomer()) {
            return null;
        }

        return $user;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 1]]
        ];
    }
}