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

	public function testIndex()
	{
		$this->logIn2();

		$user = [
			"username" => "test_user",
			"email" => "test_user@test-email-rpl.com",
			"password" => "test_password"
		];

		$response = $this->sendJSONPost("user", $user);

		$this->assertJsonResponse($response, 201);
	}
}
