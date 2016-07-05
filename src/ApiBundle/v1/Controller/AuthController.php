<?php
//
//namespace ApiBundle\v1\Controller;
//
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\Finder\Exception\AccessDeniedException;
//use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
//use AppBundle\Model\IConstructorController;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Security\Core\User\UserInterface;
//
//class AuthController extends Controller implements IConstructorController
//{
//
//	public function __init()
//	{
//	}
//
//	/**
//	 * @Route("/api/v1/auth_login")
//	 * @Method("GET")
//	 */
//	public function authLoginAction()
//	{
//		return $this->redirectToRoute('/oauth/v2/auth_login');
//	}
//
//	/**
//	 * @Route("/api/v1/auth_login_check")
//	 * @Method("GET")
//	 */
//	public function authLoginCheckAction()
//	{
//		return $this->redirectToRoute('/oauth/v2/auth_login_check');
//	}
//}