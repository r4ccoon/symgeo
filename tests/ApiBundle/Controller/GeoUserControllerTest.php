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
			"username" => "test_user" . rand(1, 200),
			"email" => "test_user" . rand(1, 200) . "@test-email-rpl.com",
			"password" => "test_password"
		];

		$response = $this->sendJSONPost("user", $user);

		$this->assertJsonResponse($response, 201);
	}
}
