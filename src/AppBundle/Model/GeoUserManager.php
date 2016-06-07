<?php

namespace AppBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class GeoUserManager extends UserManager
{
	public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, ObjectManager $om, $class)
	{
		parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $om, $class);
	}

	public function createFleetManager($params)
	{
		$user = $this->_createUser($params);
		$user->addRole('ROLE_MANAGER');

		return $user;
	}

	public function createDriver($params)
	{
		$user = $this->_createUser($params);
		$user->addRole('ROLE_DRIVER');

		return $user;
	}

	private function _createUser($params)
	{
		$user = $this->createUser();
		$user->setEnabled(true);

		$user->setUsername($params['username']);
		$user->setEmail($params['email']);
		$user->setPassword($params['password']);

		return $user;
	}

	public function deleteFleetManager($params)
	{
		$user = $this->findUserByUsername($params['username']);
		$this->deleteUser($user);

		return $user;
	}

	public function getFleetManager($params)
	{
		$user = $this->findUserByUsername($params['username']);
		return $user;
	}
}