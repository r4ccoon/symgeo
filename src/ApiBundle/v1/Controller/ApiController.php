<?php

namespace ApiBundle\v1\Controller;


use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use AppBundle\Model\IConstructorController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiController extends Controller implements IConstructorController
{
	const SUCCESS = Response::HTTP_OK;
	const SUCCESS_CREATED = Response::HTTP_CREATED;
	const FAIL_BAD_PARAMS = Response::HTTP_BAD_REQUEST;
	const FAIL_NOT_AUTHORIZED = Response::HTTP_UNAUTHORIZED;
	const FAIL_NOT_FOUND = Response::HTTP_NOT_FOUND;
	const FAIL_SERVER_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR;

	public function __init()
	{
	}

	protected function renderJSON($params, $httpCode)
	{
		$response = new JsonResponse($params);
		$response->setStatusCode($httpCode);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}