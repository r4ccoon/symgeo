<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fleet")
 */
class Fleet
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	public $user;

	/**
	 * @ORM\Column(type="string")
	 */
	public $slug;

	/**
	 * @ORM\Column(type="string")
	 */
	public $name;

	public function __construct()
	{
	}

	public function getId()
	{
		return $this->id;
	}
}