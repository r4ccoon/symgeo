<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements FixtureInterface
{
	public function load(ObjectManager $om)
	{
		$mgr = new User();
		$mgr->setUsername('manager');
		$mgr->setPassword('test');
		$mgr->setEmail("manager@rpl.im");
		$mgr->addRole('ROLE_MANAGER');
		$om->persist($mgr);
		$om->flush();

		$this->addReference('manager-user', $mgr);

		$drvr = new User();
		$drvr->setUsername('driver');
		$drvr->setPassword('test');
		$drvr->setEmail("driver@rpl.im");
		$drvr->addRole('ROLE_DRIVER');
		$om->persist($drvr);
		$om->flush();

		$this->addReference('driver-user', $drvr);
	}

	public function getOrder()
	{
		return 1;
	}
}
