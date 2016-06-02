<?php
namespace ApiBundle\v1\Controller;

use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Assetic\Filter\PackerFilter;
use Doctrine\Common\CommonException;
use Respect\Validation\Exceptions\NestedValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Respect\Validation\Validator as v;

class GeoUserController extends ApiController
{
	const USER = 'user';
	const FAIL_NOT_AUTHORIZED_MESSAGE = 'unauthorized';

	private $userManager;
	private $geoUserManager;

	public function __init()
	{
		parent::__init();

		$this->userManager = $this->get('fos_user.user_manager');
		$this->geoUserManager = $this->get('geo_user.manager');
	}

	/**
	 * @Route("/api/v1/user/")
	 * @Method("GET")
	 */
	public function getUserAction()
	{
		$user = $this->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException(
				self::FAIL_NOT_AUTHORIZED_MESSAGE);
		}

		if ($user) {
			return $this->renderDoctrineJSON(
				['user' => $user],
				self::SUCCESS);
		} else {
			throw new HttpException(401, "Not Authorised.");
		}
	}

	/**
	 * @Route("/api/v1/user/")
	 * @Method("POST")
	 */
	public function createUserAction()
	{
		$params = array();
		$content = $this->get("request")->getContent();
		if (!empty($content)) {
			try {
				$params = json_decode($content, true); // 2nd param to get as array
			} catch (Exception $e) {
				throw new HttpException(400, "Bad Parameters");
			}
		}

		try {
			v::alnum()->noWhitespace()->length(3, 32)->assert($params['username']);
			v::email()->noWhitespace()->length(3, 32)->assert($params['email']);
			v::length(6, 32)->assert($params['password']);
		} catch (NestedValidationException $exception) {
			throw new HttpException(400, $exception->getFullMessage());
		}

		// create user temporarily
		$user = $this->geoUserManager->createFleetManager($params);

		// make sure this user is granted access to do it
		$this->denyAccessUnlessGranted('create', $user, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		// update / save to DB
		$this->geoUserManager->updateUser($user);

		if ($user) {
			$this->renderJSON(
				["id" => $user->getId()],
				self::SUCCESS_CREATED);
		} else {
			throw new HttpException(400, "Cannot create user");
		}
	}
}
