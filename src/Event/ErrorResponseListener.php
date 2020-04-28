<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ErrorResponseListener
{

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse(['message' => $exception->getMessage()], $exception->getStatusCode());
        $event->setResponse($response);
    }
}