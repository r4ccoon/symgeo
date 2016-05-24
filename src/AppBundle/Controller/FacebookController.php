<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContextInterface;
use AppBundle\Model\IConstructorController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Hybrid_Auth;
use Hybrid_Endpoint;

class FacebookController extends Controller implements IConstructorController
{
	private $hybridAuth;
	private $adapter;
	private $userProfile;

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
	}

	/**
	 * @Route("/facebook-login")
	 */
	public function facebookLoginAction()
	{
		$this->adapter = $this->hybridAuth->authenticate("Facebook");
		$this->userProfile = $this->adapter->getUserProfile();
		$hybridauth_session_data = $this->hybridAuth->getSessionData();

		// then store it on your database or something
		//store_hybridauth_session($this->userProfile->identifier, $hybridauth_session_data);
		//\Hybrid_Auth::storage()->set('user', $this->userProfile->identifier);

		return $this->render(
			'default/facebook.html.twig',
			array('userProfile' => $this->userProfile, 'sessionId' => $hybridauth_session_data)
		);
	}

	/**
	 * @Route("/api/v1/oauth2/facebook")
	 */
	public function facebookCallBackAction()
	{
		if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done'])) {
			Hybrid_Endpoint::process();
		} else {
			$hybridauth_session_data = $this->hybridAuth->getSessionData();
			$this->adapter = $this->hybridAuth->authenticate("Facebook");
			$this->userProfile = $this->adapter->getUserProfile();
		}

		$identifier = Hybrid_Auth::storage()->getSessionData();

		return $this->renderView(
			'default/facebook.html.twig',
			array('userProfile' => $identifier)
		);
	}
}
