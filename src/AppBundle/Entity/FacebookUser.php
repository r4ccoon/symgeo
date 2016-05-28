<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="facebook_user")
 */
class FacebookUser
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", fetch="EAGER", cascade={"persist"})
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	public $user;

	/**
	 * @ORM\Column(type="string")
	 */
	public $identifier;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $webSiteURL;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $profileURL;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $photoURL;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $displayName;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $description;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $firstName;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $lastName;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $gender;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $language;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	public $age;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	public $birthDay;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	public $birthMonth;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	public $birthYear;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $email;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $emailVerified;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $phone;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $address;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $country;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $region;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $city;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $zip;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $username;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	public $coverInfoURL;

	public function __construct()
	{
	}
}