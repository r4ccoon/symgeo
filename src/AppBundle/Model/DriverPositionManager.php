<?php
namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;

class DriverPositionManager extends Manager
{
	const AREA = 'area';

	public function __construct(EntityManager $om)
	{
		$this->class = 'AppBundle\Entity\DriverPosition';
		parent::__construct($om);
	}

	public function findByUserId($user_id)
	{
		return $this->repository->find(
			array('user_id' => $user_id),
			array('createdAt' => 'DESC')
		);
	}

	public function findOneByUserId($user_id)
	{
		return $this->repository->find(
			array('user_id' => $user_id),
			array('createdAt' => 'DESC'),
			1
		);
	}

	public function findByRange($start, $end, $user_id = null)
	{
		$qb = $this->objectManager->createQueryBuilder();
		$qb->where("created_at BETWEEN :start AND :end")
			->setParameter("start", $start)
			->setParameter("end", $end);

		if ($user_id != null) {
			$qb->andWhere("user_id", $user_id);
		}

		$q = $qb->getQuery();
		$result = $q->getResult();
		return $result;
	}

	public function findByRadius($mode, $fromX, $fromY, $radius, $user_id)
	{
	}

	public function setFromParams($pos, $params)
	{
		$pos->user = $params['user'];
		$pos->lat = $params['lat'];
		$pos->lng = $params['lng'];

		return $pos;
	}
}