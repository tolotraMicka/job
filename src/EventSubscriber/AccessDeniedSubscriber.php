<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class AccessDeniedSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        // Redirige uniquement si c'est une exception d'accès refusé
        if ($exception instanceof AccessDeniedHttpException) {
            // Redirection vers une autre page, par exemple "page_interdite"
            $url = $this->urlGenerator->generate('page_interdite');

            $response = new RedirectResponse($url);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    
}
