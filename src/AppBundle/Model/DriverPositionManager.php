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

	public function findOneByUserId($user_id)
	{
		return $this->repository->findOneBy(
			array('user' => $user_id),
			array('createdAt' => 'DESC')
		);
	}

	public function findByUserId($user_id, AreaRange $area_range = null, TimeRange $time_range = null)
	{
		return $this->findByRange($area_range, $time_range, $user_id);
	}

	public function findByTimeRange(TimeRange $time_range, AreaRange $area_range = null, $user_id = null)
	{
		return $this->findByRange($area_range, $time_range, $user_id);
	}

	public function findByAreaRange(AreaRange $area_range, TimeRange $time_range = null, $user_id = null)
	{
		return $this->findByRange($area_range, $time_range, $user_id);
	}

	public function setFromParams($pos, $params)
	{
		$pos->user = $params['user'];
		$pos->lat = $params['lat'];
		$pos->lng = $params['lng'];

		return $pos;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function createPosition($params)
	{
		$pos = $this->create();
		$this->setFromParams($pos, $params);
		$this->update($pos);

		return $pos;
	}

	public function findByRange(AreaRange $area_range = null, TimeRange $time_range = null, $user_id = null)
	{
		$qb = $this->repository->createQueryBuilder("a");
		$qb->where("a.id > 0");

		if ($user_id != null) {
			$qb->andWhere("a.user = :user_id")
				->setParameter("user_id", $user_id);
		}

		if ($time_range != null) {
			$qb->andWhere("a.createdAt BETWEEN :start AND :end")
				->setParameter("start", $time_range->getStart())
				->setParameter("end", $time_range->getEnd());
		}

		if ($area_range != null) {
			$startAreaX = $area_range->getTopLeftX();
			$endAreaX = $area_range->getTopLeftX() + $area_range->getRadius();
			$startAreaY = $area_range->getTopLeftY();
			$endAreaY = $area_range->getTopLeftY() + $area_range->getRadius();

			$qb->andWhere("a.lat BETWEEN :startAreaX AND :endAreaX")
				->andWhere("a.lng BETWEEN :startAreaY AND :endAreaY")
				->setParameter("startAreaX", $startAreaX)
				->setParameter("endAreaX", $endAreaX)
				->setParameter("startAreaY", $startAreaY)
				->setParameter("endAreaY", $endAreaY);
		}

		$q = $qb->getQuery();
		$result = $q->getResult();
		return $result;
	}

}