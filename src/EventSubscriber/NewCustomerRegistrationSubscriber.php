<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Customer;
use App\Service\Mailer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NewCustomerRegistrationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $container;

    public function __construct(Mailer $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['sendNotification', EventPriorities::POST_WRITE],
        ];
    }

    public function sendNotification(ViewEvent $event)
    {
        $customer = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $request = $event->getRequest();
        $data = json_decode($request->getContent(), true);

        if (!$customer instanceof Customer || Request::METHOD_POST !== $method) {
            return;
        }

        $this->mailer->sendEmail(
            ['email.new_api_customer_registration'],
            null,
            'camila.ferraz@terranutri.net',
            'email/new_customer_registration.html.twig',
            ['customer' => $customer, 'data' => $data]
        );
    }
}