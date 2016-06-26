<?php
namespace AppBundle\Model;

use AppBundle\Entity\Fleet;
use AppBundle\Entity\FleetUser;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

class FleetManager extends Manager
{
	protected $fleetUserRepo;

	public function __construct(EntityManager $om)
	{
		$this->class = 'AppBundle\Entity\Fleet';
		parent::__construct($om);

		$this->fleetUserRepo = $om->getRepository('AppBundle\Entity\FleetUser');
	}

	public function findByUserId($user_id)
	{
		return $this->fleetUserRepo->findBy(
			['user' => $user_id], ['createdAt' => 'DESC']
		);
	}

	public function setCreatedBy(Fleet $fleet, $user)
	{
		$fleet->createdBy = $user;
		$this->objectManager->persist($fleet);
	}

	public function setFleetToUser($fleet, $user)
	{
		$fleetRelation = new FleetUser();
		$fleetRelation->setFleetToUser($fleet, $user);
		$this->update($fleetRelation, true);
	}

	public function setFromParams(Fleet $fleet, $params)
	{
		$fleet->createdBy = $params['created_by'];
		$fleet->name = $params['name'];

		$slugify = new Slugify();
		$fleet->slug = $slugify->slugify($params['name']);

		return $fleet;
	}
}