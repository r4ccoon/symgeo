<?php

namespace Tests\ApiBundle\Controller;

use AppBundle\Model\DriverPositionManager;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class PositionControllerTest extends BaseTestController
{
	protected $password = "test_password";

	public function __construct()
	{
		parent::__construct();
	}

	public function setUp()
	{
		parent::setUp();
		$this->username = "test_user" . rand(1, 200);

		$this->logIn();
	}


	public function testCreateUser()
	{
		$uid = $this->createDriver();
		return $uid;
	}

	/**
	 * @depends testCreateUser
	 */
	public function testCreatePosition($user)
	{
		$position = [
			"lat" => 37.393885,
			"lng" => -122.078916
		];

		$this->logIn($user['username'], $user['password']);

		$response = $this->sendJSONPost("position", $position);
		$this->assertJsonResponse($response, 201);

		$result = json_decode($response->getContent());
		$this->assertEquals($position['lat'], $result->position->lat);
		$this->assertEquals($position['lng'], $result->position->lng);
		$this->assertNotNull($result->position->user);

		return $result->position->id;
	}

	/**
	 * @depends testCreateUser
	 * @depends testCreatePosition
	 */
	public function testDeleteUser($user, $position)
	{
		/**
		 * @var DriverPositionManager
		 */
		$pM = $this->container->get('position.manager');
		$pM->deleteBy(['user' => $user['id']]);

		$response = $this->sendJSONDelete("user", $user);
		$this->assertJsonResponse($response, 200);
	}

	private function createDriver()
	{
		$user = [
			"username" => $this->username,
			"email" => $this->username . "@test-email-rpl.com",
			"password" => $this->password
		];

		$response = $this->sendJSONPost("user/driver", $user);
		$obj = json_decode($response->getContent());
		$user['id'] = $obj->id;
		return $user;
	}
}
