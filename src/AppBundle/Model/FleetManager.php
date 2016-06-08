<?php
namespace AppBundle\Model;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

class FleetManager extends Manager
{
	public function __construct(EntityManager $om)
	{
		$this->class = 'AppBundle\Entity\Fleet';
		parent::__construct($om);
	}

	public function getFleetByUserId($user_id)
	{
		return $this->repository->findOneBy(
			array('user_id' => $user_id)
		);
	}

	public function setUser($fleet, $user)
	{
		$fleet->user = $user;
		$this->objectManager->persist($fleet);
	}

	public function setFromParams($fleet, $params)
	{
		$fleet->user = $params['user'];
		$fleet->name = $params['name'];

		$slugify = new Slugify();
		$fleet->slug = $slugify->slugify($params['name']);

		return $fleet;
	}
}