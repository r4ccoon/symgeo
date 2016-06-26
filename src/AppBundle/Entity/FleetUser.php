<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fleet_user")
 */
class FleetUser extends BaseEntity
{
	/**
	 * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	public $user;

	/**
	 * @ORM\ManyToOne(targetEntity="Fleet", fetch="EAGER")
	 * @ORM\JoinColumn(name="fleet_id", referencedColumnName="id", nullable=false)
	 *
	 */
	public $fleet;

	public function setFleetToUser(Fleet $fleet, User $user)
	{
		$this->user = $user;
		$this->fleet = $fleet;
	}
}