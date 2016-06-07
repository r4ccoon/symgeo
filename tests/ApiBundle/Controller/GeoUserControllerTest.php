<?php

namespace Tests\ApiBundle\Controller;

use stdClass;
use Symfony\Component\HttpFoundation\Response;

class GeoUserControllerTest extends BaseTestController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function setUp()
	{
		parent::setUp();
		$this->username = "test_user" . rand(1, 200);
		$this->logIn2();
	}

	public function testIndex()
	{
		$user = [
			"username" => $this->username,
			"email" => $this->username . "@test-email-rpl.com",
			"password" => "test_password"
		];

		$response = $this->sendJSONPost("user", $user);

		$this->assertJsonResponse($response, 201);

		return $this->username;
	}

	/**
	 * @depends testIndex
	 */
	public function testDelete($username)
	{
		$user = [
			"username" => $username
		];

		$response = $this->sendJSONDelete("user", $user);

		$this->assertJsonResponse($response, 200);
	}
}
