<?php
namespace AppBundle\Model;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

class DriverPositionManager extends Manager
{
	public function __construct(EntityManager $om)
	{
		$this->class = 'AppBundle\Entity\DriverPosition';
		parent::__construct($om);
	}

	public function findByUserId($user_id)
	{
		return $this->repository->find(
			array('user_id' => $user_id)
		);
	}
}