<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\DriverPosition;
use AppBundle\Model\DriverPositionManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPositionData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var DriverPositionManager
	 */
	private $positionManager;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	public function load(ObjectManager $om)
	{
		$this->positionManager = $this->container->get('position.manager');

		$params = [
			'lat' => '50.7531987',
			'lng' => '7.0912389',
			'user' => $this->getReference('driver-user')
		];

		$pos = $this->positionManager->create();
		$this->positionManager->setFromParams($pos, $params);
		$this->positionManager->update($pos);
	}

	public function getOrder()
	{
		return 2;
	}
}
