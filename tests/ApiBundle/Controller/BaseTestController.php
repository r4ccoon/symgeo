<?php

namespace Tests\ApiBundle\Controller;

use FOS\UserBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class BaseTestController extends WebTestCase
{
	protected $client = null;
	protected $baseUrl = "/api/v1/";
	protected $session;
	protected $storage;
	protected $container;
	protected $cookie;
	protected $cookieJar;
	protected $token;

	public function setUp()
	{
		$this->client = static::createClient();
	}

	public function logIn($username = 'admin', $password = null)
	{
		$session = $this->client->getContainer()->get('session');

		$firewall = 'main';
		$token = new UsernamePasswordToken($username, $password, $firewall, array('ROLE_ADMIN'));
		$session->set('_security_' . $firewall, serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);
	}


	/*
	public function getUserManager()
	{
		return $this->container->get('geo_user.manager');
	}
	public function getSecurityManager()
	{
		return $this->container->get('fos_user.security.login_manager');
	}

	public function getUser($role = null)
	{
		if (!isset($this->user)) {
			$user = $this->getUserManager()->findUserByUsername('user');

			if (isset($user)) {
				$this->user = $user;
			} else {
				$this->user = $this->getUserManager()->createUser();

				$this->user->setEnabled(true);
				$this->user->setUsername('user');
				$this->user->setEmail('user@rpl.im');
				$this->user->setPlainPassword('user');

				if (isset($role)) {
					$this->user->addRole($role);
				}

				$this->getUserManager()->updateUser($this->user);
			}
		}

		return $this->user;
	}

	public function logIn(User $user, Response $response)
	{
		$this->session->start();

		$this->cookie = new Cookie('MOCKSESSID', $this->storage->getId());
		$this->cookieJar = new CookieJar();
		$this->cookieJar->set($this->cookie);
		$this->token = new UsernamePasswordToken($user, 'user', 'main', $user->getRoles());
		$this->session->set('_security_main', serialize($this->token));

		$this->getSecurityManager()->loginUser(
			$this->container->getParameter('fos_user.firewall_name'),
			$user,
			$response
		);

		$this->session->save();
	}*/

	/**
	 * @param $resUri string
	 * @param $body object (will be converted to json
	 * @param $uriParams array
	 * @return
	 */
	protected function sendJSONPost($resUri, $body, $uriParams = null)
	{
		if (is_object($body) || is_array($body))
			$body = json_encode($body);

		$client = $this->client;
		$client->request(
			'POST', // method
			$this->prepareUrl($resUri, $uriParams), // url
			[], // parameters
			[], // files
			[
				'CONTENT_TYPE' => 'application/json'
			], // headers
			$body // body
		);

		$response = $client->getResponse();

		return $response;
	}

	protected function sendJSONDelete($resUri, $body, $uriParams = null)
	{
		if (is_object($body) || is_array($body))
			$body = json_encode($body);

		$client = $this->client;
		$client->request(
			'DELETE', // method
			$this->prepareUrl($resUri, $uriParams), // url
			[], // parameters
			[], // files
			[
				'CONTENT_TYPE' => 'application/json'
			], // headers
			$body // body
		);

		$response = $client->getResponse();

		return $response;
	}

	protected function assertJsonResponse($response, $statusCode = 200)
	{
		$this->assertEquals(
			$statusCode, $response->getStatusCode(),
			$response->getContent()
		);

		$this->assertTrue(
			$response->headers->contains('Content-Type', 'application/json'),
			$response->headers
		);
	}

	private function prepareUrl($resUri, $uriParams = null)
	{
		//todo: $uriParams
		return $this->baseUrl . $resUri;
	}
}
