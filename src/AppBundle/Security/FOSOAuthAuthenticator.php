<?php
namespace AppBundle\Security;

use FOS\OAuthServerBundle\Security\Authentication\Provider\OAuthProvider;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use OAuth2\OAuth2;
use OAuth2\OAuth2AuthenticateException;
use OAuth2\OAuth2ServerException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FOSOAuthAuthenticator extends AbstractGuardAuthenticator
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	/**
	 * @var OAuth2
	 */
	protected $serverService;


	public function __construct(ContainerInterface $container, OAuth2 $serverService)
	{
		$this->container = $container;
		$this->serverService = $serverService;

		$o = 'afs';
	}

	/**
	 * {@inheritdoc}
	 */
	public function start(Request $request, AuthenticationException $authException = null)
	{
		$exception = new OAuth2AuthenticateException(
			OAuth2::HTTP_UNAUTHORIZED,
			OAuth2::TOKEN_TYPE_BEARER,
			$this->serverService->getVariable(OAuth2::CONFIG_WWW_REALM),
			'access_denied',
			'OAuth2 authentication required'
		);

		return new JsonResponse($exception, 401);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCredentials(Request $request)
	{
		if (null === $oauthToken = $this->serverService->getBearerToken($request, true)) {
			return;
		}

		$token = new OAuthToken();
		$token->setToken($oauthToken);

		return $token;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		try {
			$tokenString = $credentials->getToken();

			if ($accessToken = $this->serverService->verifyAccessToken($tokenString)) {
				//$scope = $accessToken->getScope();
				$user = $accessToken->getUser();

				return $user;
			}
		} catch (OAuth2ServerException $oauthError) {
			throw new AuthenticationException('OAuth2 authentication failed');

		}

		throw new AuthenticationException('OAuth2 authentication failed');

	}

	/**
	 * {@inheritdoc}
	 */
	public function checkCredentials($credentials, UserInterface $user)
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$data = array(
			'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

			// or to translate this message
			// $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
		);

		return new JsonResponse($data, 403);
	}

	/**
	 * {@inheritdoc}
	 */
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		// on success, let the request continue
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function supportsRememberMe()
	{
		return false;
	}
}