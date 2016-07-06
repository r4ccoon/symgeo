<?php
namespace AppBundle\Model;

use Doctrine\ORM\EntityManager;

abstract class Manager
{
	protected $objectManager;
	protected $repository;
	protected $class;

	public function __construct(EntityManager $om)
	{
		$this->objectManager = $om;
		$this->repository = $om->getRepository($this->class);
	}

	public function create()
	{
		$class = $this->class;
		$fleet = new $class;

		return $fleet;
	}

	public function findOneById($id)
	{
		return $this->repository->findOneBy(
			array('id' => $id)
		);
	}

	public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
	{
		return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
	}

	public function delete($obj)
	{
		$this->objectManager->remove($obj);
		$this->objectManager->flush();
	}

	public function deleteBy($params)
	{
		$qb = $this->objectManager->createQueryBuilder();
		if (is_array($params)) {
			$qb->delete()->from($this->class, 'a');
			$c = 0;
			foreach ($params as $key => $val) {
				if ($c == 0)
					$qb = $qb->where("a.$key = :$key");
				else
					$qb = $qb->andWhere("a.$key = :$key");

				$c++;
			}

			$qb->setParameters($params);
			$q = $qb->getQuery();
			$result = $q->execute();

			return $result;
		}

		return null;
	}

	public function update($obj, $andFlush = true)
	{
		$this->objectManager->persist($obj);
		if ($andFlush) {
			$this->objectManager->flush();
		}
	}
}