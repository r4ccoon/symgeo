<?php
namespace AppBundle\Controller;

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

class PanelController extends Controller implements IConstructorController
{
	private $userManager;
	private $facebookManager;

	public function __init()
	{
		$this->userManager = $this->get('fos_user.user_manager');
		$this->facebookManager = $this->get('facebook.manager');
	}

	/**
	 * @Route("/panel/", name="panel")
	 */
	public function indexAction()
	{
		$user = $this->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}

		return $this->render(
			'panel/index.html.twig',
			array('user' => $user)
		);

	}
}
