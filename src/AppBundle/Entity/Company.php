<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class Company extends BaseEntity
{
	/**
	 * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", cascade={"persist"})
	 * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
	 *
	 */
	public $owner;

	/**
	 * @ORM\Column(type="string")
	 */
	public $name;
	
	/**
	 * @ORM\Column(type="string")
	 */
	public $slug;
}