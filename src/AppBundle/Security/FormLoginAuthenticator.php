<?php
namespace AppBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
	private $container;
	private $tokenStorage;

	public function __construct(ContainerInterface $container, TokenStorageInterface $tokenStorage)
	{
		$this->container = $container;
		$this->tokenStorage = $tokenStorage;
	}

	public function getCredentials(Request $request)
	{
		if ($request->getPathInfo() != '/login_check') {
			return;
		} else {
			$username = $request->request->get('_username');
			$request->getSession()->set(Security::LAST_USERNAME, $username);
			$password = $request->request->get('_password');

			return [
				'username' => $username,
				'password' => $password
			];
		}
	}

	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		$username = $credentials['username'];
		return $userProvider->loadUserByUsername($username);
	}

	public function checkCredentials($credentials, UserInterface $user)
	{
		$plainPassword = $credentials['password'];
		$encoder = $this->container->get('security.password_encoder');

		if (!$encoder->isPasswordValid($user, $plainPassword)) {
			// throw any AuthenticationException
			throw new BadCredentialsException();
		}
	}

	protected function getLoginUrl()
	{
		return $this->container->get('router')
			->generate('fos_user_security_login');
	}

	protected function getDefaultSuccessRedirectUrl()
	{
		return $this->container->get('router')
			->generate('homepage');
	}
}