<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="driver_position")
 * @ORM\HasLifecycleCallbacks
 */
class DriverPosition
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="bigint")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	public $user;

	/**
	 * @ORM\Column(type="float", precision=10, scale=6)
	 */
	public $lat;

	/**
	 * @ORM\Column(type="float", precision=10, scale=6)
	 */
	public $lng;

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