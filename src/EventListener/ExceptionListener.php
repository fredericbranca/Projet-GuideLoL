<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class ExceptionListener
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        // Récupère l'exception
        $exception = $event->getThrowable();

        // Gère uniquement les exceptions NotFoundHttpException
        if ($exception instanceof NotFoundHttpException) {
            $response = new Response();
            $response->setContent($this->twig->render('bundles/TwigBundle/Exception/error404.html.twig'));
            $response->setStatusCode(Response::HTTP_NOT_FOUND);

            $event->setResponse($response);
        } else if ($exception->getStatusCode() == 403) {
            $response = new Response();
            $response->setContent($this->twig->render('bundles/TwigBundle/Exception/error404.html.twig'));
            $event->setResponse($response);
        } else {
            $response = new Response();
            $response->setContent($this->twig->render('bundles/TwigBundle/Exception/error.html.twig'));
            $event->setResponse($response);
        }
    }
}
