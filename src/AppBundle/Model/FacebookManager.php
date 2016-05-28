<?php
namespace AppBundle\Model;

use AppBundle\Entity\FacebookUser;
use Doctrine\ORM\EntityManager;

class FacebookManager
{
	protected $objectManager;
	protected $repository;
	protected $class;

	public function __construct(EntityManager $om)
	{
		$class = 'AppBundle\Entity\FacebookUser';
		$this->objectManager = $om;
		$this->repository = $om->getRepository($class);

		$metadata = $om->getClassMetadata($class);
		$this->class = $metadata->getName();
	}

	public function findFBUserByIdentifier($identifier)
	{
		return $this->repository->findOneBy(
			array('identifier' => $identifier)
		);
	}

	public function createFBUser($userProfile)
	{
		$fbUser = new FacebookUser();
		$this->objectManager->persist($fbUser);

		foreach ($userProfile as $key => $val) {
			$fbUser->{$key} = $val;
		}

		$this->updateFBUser($fbUser);

		return $fbUser;
	}

	public function setUserByFBProfile($fbUser, $user)
	{
		$user->setEnabled(true);
		$user->setUsername($fbUser->identifier);
		$user->setEmail($fbUser->email);
		$user->setPlainPassword($this->randomPassword());
		$user->setEnabled(true);
		$user->setSuperAdmin(false);
		$user->addRole("ROLE_USER");

		// link this user to fb user
		$this->linkUser($fbUser, $user);
		$this->updateFBUser($fbUser);
	}

	/**
	 * Updates a user.
	 *
	 * @param UserInterface $user
	 * @param Boolean $andFlush Whether to flush the changes (default true)
	 */
	public function updateFBUser($fbUser, $andFlush = true)
	{
		$this->objectManager->persist($fbUser);
		if ($andFlush) {
			$this->objectManager->flush();
		}
	}

	/**
	 * @param $username
	 *
	 * link an existing user with this login from FB
	 */
	public function linkUser($userProfile, $user)
	{
		if (!$userProfile->user) {
			$userProfile->user = $user;

			$this->updateFBUser($userProfile);
		}
	}

	private function randomPassword($length = 8)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$password = substr(str_shuffle($chars), 0, $length);
		return $password;
	}


}