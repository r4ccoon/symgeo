<?php

namespace Tests\AppBundle\Model;

use AppBundle\DataFixtures\ORM\LoadPositionData;
use AppBundle\DataFixtures\ORM\LoadUserData;
use AppBundle\Model\AreaRange;
use AppBundle\Model\DriverPositionManager;
use AppBundle\Model\TimeRange;

class DriverPositionManagerTest extends FixtureAwareTestCase
{
	private $user;
	private $username = 'driver';
	/**
	 * @var DriverPositionManager
	 */
	private $dpm;

	public function setUp()
	{
		parent::setUp();

		// Base fixture for all tests
		$this->addFixture(new LoadUserData());
		$this->addFixture(new LoadPositionData());
		$this->executeFixtures();

		$gm = $this->getContainer()->get("geo_user.manager");
		$this->dpm = $this->getContainer()->get("position.manager");
		$this->user = $gm->findUserBy(['username' => $this->username]);
	}

	public function testFindByUserId()
	{
		$positions = $this->dpm->findByUserId($this->user->getId());
		$this->assertNotNull($positions);
		$this->assertTrue(is_array($positions));
		$this->assertEquals(50.7531987, $positions[0]->lat);
	}

	public function testFindOneByUserId()
	{
		$position = $this->dpm->findOneByUserId($this->user->getId());
		$this->assertNotNull($position);
		$this->assertEquals(50.7531987, $position->lat);
	}

	public function testfindByTimeRange()
	{
		// success test, when we can find the loaded fake loc
		// between yesterday and tomorrow
		$dt = new \DateTime();
		$dt->setTimestamp(time() - 1000);
		$dt2 = new \DateTime();
		$dt2->setTimestamp(time() + 1000);

		$time_range = new TimeRange($dt, $dt2);
		$position = $this->dpm->findByTimeRange($time_range);
		$this->assertNotNull($position);
		$this->assertEquals(50.7531987, $position[0]->lat);
	}

	public function testFindByTimeRangeFuture()
	{
		// failed test, when we cant find position because it looks in the future
		$dt = new \DateTime();
		$dt->setTimestamp(time() + 1000);
		$dt2 = new \DateTime();
		$dt2->setTimestamp(time() + 2000);

		$time_range = new TimeRange($dt, $dt2);
		$position = $this->dpm->findByTimeRange($time_range);
		$this->assertNotNull($position);
		$this->assertEquals(0, count($position));
	}

	public function testFindByTimeRangePast()
	{
		// failed test, when we cant find position because it looks in the past
		$dt = new \DateTime();
		$dt->setTimestamp(time() - 2000);
		$dt2 = new \DateTime();
		$dt2->setTimestamp(time() - 1000);

		$time_range = new TimeRange(time() - 200000, time() - 100000);
		$position = $this->dpm->findByTimeRange($time_range);
		$this->assertNotNull($position);
		$this->assertEquals(0, count($position));
	}

	public function testFindByAreaRange()
	{
		$params = [
			'lat' => '50.7531987',
			'lng' => '7.0912389'
		];

		$area = new AreaRange($params['lat'], $params['lng'], 10000);
		$position = $this->dpm->findByAreaRange($area);
		$this->assertNotNull($position);
		$this->assertEquals(50.7531987, $position[0]->lat);
	}

	public function testFindByAreaRangeOutOfBound()
	{
		$params = [
			'lat' => '50.7531987',
			'lng' => '7.0912389'
		];

		//0.009000009 = 1000/111111
		$area = new AreaRange($params['lat'] + 0.009000009, $params['lng'] + 0.009000009, 1000);
		$position = $this->dpm->findByAreaRange($area);
		$this->assertNotNull($position);
		$this->assertEquals(0, count($position));
	}

	public function testFindByAreaRangeInBound()
	{
		$params = [
			'lat' => '50.7531987',
			'lng' => '7.0912389'
		];

		$area = new AreaRange($params['lat'] - 0.009000009, $params['lng'] - 0.009000009, 2000);
		$position = $this->dpm->findByAreaRange($area);
		$this->assertNotNull($position);
		$this->assertEquals(1, count($position));
		$this->assertEquals(50.7531987, $position[0]->lat);
	}
}