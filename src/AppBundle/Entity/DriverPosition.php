<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="driver_position")
 * @ORM\HasLifecycleCallbacks
 */
class DriverPosition extends BaseEntity
{
	/**
	 * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	public $user;

	/**
	 * @ORM\ManyToOne(targetEntity="Fleet")
	 * @ORM\JoinColumn(name="fleet_id", referencedColumnName="id", nullable=true)
	 */
	public $fleet;

	/**
	 * @ORM\Column(type="float", precision=10, scale=6)
	 */
	public $lat;

	/**
	 * @ORM\Column(type="float", precision=10, scale=6)
	 */
	public $lng;
}