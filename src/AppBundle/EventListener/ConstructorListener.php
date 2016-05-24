<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContextInterface;
use AppBundle\Controller\IConstructorController;

class ConstructorListener
{
	public function __construct()
	{
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		$controller = $event->getController();
		if (!is_array($controller)) {
			// not a object but a different kind of callable. Do nothing
			return;
		}

		$controllerObject = $controller[0];
		if ($controllerObject instanceof ExceptionController) {
			return;
		}

		if (in_array('AppBundle\Model\IConstructorController', class_implements($controllerObject))) {
			// this method is the one that is part of the interface.
			$controllerObject->__init();
		}
	}
}