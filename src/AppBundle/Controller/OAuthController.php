<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContextInterface;
use AppBundle\Model\IConstructorController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthController extends Controller implements IConstructorController
{
	public function __init()
	{

	}

	/**
	 * @Route("/oauth2/create-client")
	 */
	public function createClientAction()
	{
		$user = $this->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}

		$client = $this->createClient();

		$authUrl = $this->generateUrl('fos_oauth_server_authorize', array(
			'client_id' => $client->getPublicId(),
			'redirect_uri' => '/oauth2/app',
			'response_type' => 'code'
		));

		return $this->render(
			'default/createClient.html.twig',
			array('client' => $client, 'authUrl' => $authUrl)
		);
	}

	/**
	 * @Route("/oauth2/user")
	 */
	public function user()
	{
		$user = $this->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}

		return $this->renderView('default/dump.html.twig', array('var' => $user));
	}

	/**
	 * @Route("/oauth2/app")
	 */
	public function appAction()
	{
		$html = "";
		return new Response($html);
	}

	private function createClient()
	{
		/*$user = $this->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}*/

		$securityContext = $this->container->get('security.context');
		if (!$securityContext->isGranted('ROLE_ADMIN')) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}

		$clientManager = $this->container->get('fos_oauth_server.client_manager.default');
		$client = $clientManager->createClient();
		$client->setRedirectUris(array('/oauth2/app'));
		$client->setAllowedGrantTypes(array('token', 'authorization_code'));
		$clientManager->updateClient($client);

		return $client;
	}
}