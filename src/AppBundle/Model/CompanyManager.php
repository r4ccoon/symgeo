<?php
namespace AppBundle\Model;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

class CompanyManager extends Manager
{
	public function __construct(EntityManager $om)
	{
		$this->class = 'AppBundle\Entity\Company';
		parent::__construct($om);
	}

	public function setOwner($company, $user)
	{
		$company->owner = $user;
		$this->objectManager->persist($company);
	}

	public function setFromParams($company, $params)
	{
		$company->owner = $params['owner'];
		$company->name = $params['name'];

		$slugify = new Slugify();
		$company->slug = $slugify->slugify($params['name']);

		return $company;
	}
}