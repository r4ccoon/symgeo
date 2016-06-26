<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(name="created_at", type="datetime", nullable=false)
	 */
	public $createdAt;

	public function __construct()
	{
	}

	public function getId()
	{
		return $this->id;
	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$dt = new \DateTime();
		$dt->setTimestamp(time());
		$this->createdAt = $dt;
	}
}

