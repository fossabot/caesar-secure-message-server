<?php

declare(strict_types=1);

namespace App\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

class ErrorResponseListener
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $this->logError($exception);

        $response = new JsonResponse(['message' => $exception->getMessage()], $exception->getStatusCode());
        $event->setResponse($response);
    }

    private function logError(Throwable $exception)
    {
        $context = [
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
        $this->logger->error($exception->getMessage(), $context);
    }
}