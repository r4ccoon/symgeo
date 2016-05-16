<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Hybrid_Auth;

class FacebookController extends Controller
{
	private $userProfile;

	/**
	 * @Route("/facebook-login")
	 */
	public function facebookLoginAction()
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

		$hybridAuth = new Hybrid_Auth( $config );

		$adapter = $hybridAuth->authenticate("Facebook");
		$this->userProfile = $adapter->getUserProfile();

	}

	/**
	 * @Route("/api/v1/oauth2/facebook")
	 */
	public function facebookCallBackAction()
	{
		$html = $this->render(
			'default/facebook.html.twig',
			array('userProfile' => $this->userProfile)
		);

		return new Response($html);
	}
}
