<?php
namespace AppBundle\Controller;

use Assetic\Filter\PackerFilter;
use Doctrine\Common\CommonException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use AppBundle\Model\IConstructorController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Hybrid_Auth;
use Hybrid_Endpoint;

class FacebookController extends Controller implements IConstructorController
{
	private $hybridAuth;
	private $adapter;
	private $userProfile;
	private $userManager;
	private $facebookManager;

	public function __init()
	{
		$conf = $this->container->getParameter('app.social_login');

		$config = [
			"base_url" => $conf['facebook']['base_url'],
			"providers" => [
				"Facebook" => [
					"enabled" => $conf['facebook']['enabled'],
					"keys" => [
						"id" => $conf['facebook']['app_id'],
						"secret" => $conf['facebook']['app_secret']
					],
					"scope" => "email"
				]
			]
		];

		$this->hybridAuth = new Hybrid_Auth($config);
		$this->userManager = $this->get('fos_user.user_manager');
		$this->facebookManager = $this->get('facebook.manager');
	}

	/**
	 * @Route("/facebook/login")
	 */
	public function facebookLoginAction()
	{
		try {
			$this->adapter = $this->hybridAuth->authenticate("Facebook");
			$this->userProfile = $this->adapter->getUserProfile();

			$this->loginUser($this->userProfile);

			return $this->redirectToRoute('panel', [], 301);
		} catch (Exception $e) {
			throw new AccessDeniedException();
		}
	}

	/**
	 * @Route("/facebook/app-login")
	 */
	public function appFacebookAppLoginAction()
	{
		$this->adapter = $this->hybridAuth->authenticate("Facebook");
		$this->userProfile = $this->adapter->getUserProfile();
		$hybridauth_session_data = $this->hybridAuth->getSessionData();

		$required = ['client_id', 'redirect_uri', 'response_type'];
		$errs = [];
		foreach ($required as $req)
			if (!$_REQUEST[$req])
				array_push($errs, $req . " is not provided.");

		if (count($errs) > 0)
			throw new BadRequestHttpException(join("\n", $errs));

		foreach ($required as $req)
			$_SESSION[$req] = $_REQUEST[$req];

		$_SESSION['is_app'] = true;

		// will be redirected by HAuth below
		// this return will not be executed ?
		return $this->render(
			'default/facebook.html.twig',
			array('userProfile' => $this->userProfile, 'sessionId' => $hybridauth_session_data)
		);
	}

	/**
	 * @Route("/facebook/callback")
	 */
	public function facebookCallBackAction()
	{
		if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done'])) {
			Hybrid_Endpoint::process();
		} else {
			$this->adapter = $this->hybridAuth->authenticate("Facebook");
			$this->userProfile = $this->adapter->getUserProfile();
		}

		$this->loginUser($this->userProfile);

		// via app
		if ($_SESSION['is_app']) {
			// redirect to get temp token
			///oauth/v2/auth?client_id=1_3sgf3wcfwbok0g8k4sc8ccw4844o4ggok8c8og48wc0okoo8wo&redirect_uri=/oauth2/app&response_type=code
			$params = [$_SESSION['client_id'], $_SESSION['redirect_uri'], $_SESSION['response_type']];
			return $this->redirectToRoute('/oauth/v2/auth/', $params, 301);
		}

		// via web
		return $this->redirectToRoute('panel', [], 301);
	}

	/**
	 * @Route("/facebook/deauthorize")
	 */
	public function facebookDeauthorizeAction()
	{
		return new Response("");
	}

	private function loginUser($userProfile)
	{
		$user = $this->getUser();
		$fbUser = $this->facebookManager->findFBUserByIdentifier($userProfile->identifier);
		if (!$fbUser) {
			// fb user is not in the system, create new one
			$fbUser = $this->facebookManager->createFBUser($userProfile);
		}

		if ($fbUser) {
			if (!is_object($user) || !$user instanceof UserInterface) {
				// if not logged in -> register user
				$user = $fbUser->user;
				if (!$user) {
					$user = $this->userManager->createUser(); 
					// user cannot be found, this is a stray fb user or new fb user
					$this->facebookManager->setUserByFBProfile($fbUser, $user);
					$this->userManager->updateUser($user);
				}

				$this->authenticateUser($user);
			} else {
				// someone is logged in
				if ($fbUser->user) {
					if ($user->getId() != $fbUser->user->getId()) {
						// if user has fb and if fb user != user.fb_user
						// logout the other one. log in this one.
						$this->logout();
						// and login this user
						$this->authenticateUser($fbUser->user);
					} else {
						// is linked and logged in
					}
				} else {
					// if logged in -> link user with this FB login
					$this->facebookManager->linkUser($fbUser, $user);
				}
			}
		} else {
			throw new CommonException("Something wrong with FB Login");
		}
	}

	private function logout()
	{
		$this->get('session')->invalidate();
	}

	private function authenticateUser($user)
	{
		// get $user from username / fb uid
		$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
		$this->get('security.token_storage')->setToken($token);
		$this->get('session')->set('_security_main', serialize($token));
	}


}
