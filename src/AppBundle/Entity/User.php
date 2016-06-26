<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @ORM\ManyToOne(targetEntity="Company")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=true)
	 *
	 */
	public $company;
} 