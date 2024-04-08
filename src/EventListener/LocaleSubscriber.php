<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct(ContainerInterface $container)
    {
        $this->defaultLocale = $container->getParameter('locale');
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $url_prefix = explode('_', $request->get('_route'), 2);
        if ($request->attributes->getBoolean('_stateless')) {
            return;
        }
        if (!$request->hasPreviousSession() or $url_prefix[0] === 'api') {
            return;
        }

        $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        );
    }
}