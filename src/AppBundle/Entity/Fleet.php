<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fleet")
 */
class Fleet extends BaseEntity
{
	/**
	 * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", cascade={"persist"})
	 * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=false)
	 */
	public $createdBy;

	/**
	 * @ORM\ManyToOne(targetEntity="Company")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
	 *
	 */
	public $company;

	/**
	 * @ORM\Column(type="string")
	 */
	public $slug;

	/**
	 * @ORM\Column(type="string")
	 */
	public $name;
}