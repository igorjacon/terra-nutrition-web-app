<?php

namespace App\EventSubscriber;

use \ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Customer;
use App\Entity\User;
use App\Repository\ProfessionalRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerSetupSubscriber implements EventSubscriberInterface
{
    private ProfessionalRepository $professionalRepository;
    private UserPasswordHasherInterface $hasher;

    public function __construct(ProfessionalRepository $professionalRepository, UserPasswordHasherInterface $hasher)
    {
        $this->professionalRepository = $professionalRepository;
        $this->hasher = $hasher;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setupCustomer', EventPriorities::POST_VALIDATE],
        ];
    }

    public function setupCustomer(ViewEvent $event)
    {
        $customer = $event->getControllerResult();
        $firstProfessional = $this->professionalRepository->findOneBy([]);
        $request = $event->getRequest();
        $data = json_decode($request->getContent(), true);

        if (!$customer instanceof Customer || Request::METHOD_POST !== $event->getRequest()->getMethod()) {
            return;
        }

        if ($firstProfessional) {
            $firstProfessional->addCustomer($customer);
        }

        $user = $customer->getUser();
//        $user->setEnabled(false);
        $user->setPassword($this->hasher->hashPassword($user, $data['user']['password']));
        $user->addRole(User::ROLES_ALLOWED[User::ROLE_CUSTOMER]);
        $customer->setRegistrationComplete(true);
    }
}