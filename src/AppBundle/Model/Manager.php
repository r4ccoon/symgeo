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

	public function delete($obj)
	{
		$this->objectManager->remove($obj);
		$this->objectManager->flush();
	}

	public function update($obj, $andFlush = true)
	{
		$this->objectManager->persist($obj);
		if ($andFlush) {
			$this->objectManager->flush();
		}
	}
}