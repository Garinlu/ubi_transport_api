<?php


namespace App\EventListener;


use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ApiException) {
            $response = new Response();
            $response->setContent($exception->getMessage());
            $response->setStatusCode($exception->getCode() ? $exception->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR);
            $event->setResponse($response);
        } else {
            // Save exception trace/message/code in a log file or in database
        }
    }
}
