<?php

namespace Tests\ApiBundle\Controller;

use stdClass;
use Symfony\Component\HttpFoundation\Response;

class FleetControllerTest extends BaseTestController
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
		$uid = $this->createUser();

		return $uid;
	}

	/**
	 * @depends testCreateUser
	 */
	public function testCreateFleet($uid)
	{
		$fleet = [
			"name" => $this->username,
			"created_by" => $uid
		];

		$response = $this->sendJSONPost("fleet", $fleet);
		$this->assertJsonResponse($response, 201);

		$result = json_decode($response->getContent());
		$this->assertEquals($fleet['name'], $result->fleet->name);
		$this->assertNotNull($result->fleet->created_by);

		return $result->fleet->id;
	}

	/**
	 * @depends testCreateFleet
	 */
	public function testDeleteFleet($id)
	{
		$fleet = [
			"id" => $id
		];

		$response = $this->sendJSONDelete("fleet", $fleet);

		$this->assertJsonResponse($response, 200);
	}

	/**
	 * @depends testCreateUser
	 * @depends testDeleteFleet
	 */
	public function testDeleteUser($user_id, $fleet)
	{
		$user = [
			"id" => $user_id
		];

		$response = $this->sendJSONDelete("user", $user);
		$this->assertJsonResponse($response, 200);
	}

	private function createUser()
	{
		$user = [
			"username" => $this->username,
			"email" => $this->username . "@test-email-rpl.com",
			"password" => $this->password
		];

		$response = $this->sendJSONPost("user", $user);
		$uid = json_decode($response->getContent());
		return $uid->id;
	}
}
