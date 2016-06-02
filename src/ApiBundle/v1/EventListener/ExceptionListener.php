<?php

namespace ApiBundle\v1\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		$request = $event->getRequest();
		if (strstr($request->getPathInfo(), '/api/v1') !== false) {
			// You get the exception object from the received event
			$exception = $event->getException();

			$err = [
				'message' => $exception->getMessage(),
				'code' => $exception->getCode()
			];

			$response = new JsonResponse($err);
			//$response->setStatusCode($exception->getStatusCode());
			$response->headers->set('Content-Type', 'application/json');

			// HttpExceptionInterface is a special type of exception that
			// holds status code and header details
			if ($exception instanceof HttpExceptionInterface) {
				$response->setStatusCode($exception->getStatusCode());
				$response->headers->replace($exception->getHeaders());
			} else {
				$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			}

			// Send the modified response object to the event
			$event->setResponse($response);
		}

		return;
	}
}
