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
		$user = $this->createUser();
		$user->setEnabled(true);

		$user->setUsername($params['username']);
		$user->setEmail($params['email']);
		$user->setPassword($params['password']);
		$user->addRole('ROLE_FLEET_MANAGER');

		$this->updateUser($user);
		return $user;
	}
}