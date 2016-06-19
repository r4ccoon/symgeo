<?php
namespace ApiBundle\v1\Controller;

use AppBundle\Model\FleetManager;
use AppBundle\VoterEvent;
use FOS\UserBundle\Model\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Assetic\Filter\PackerFilter;
use Doctrine\Common\CommonException;
use Respect\Validation\Exceptions\NestedValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Respect\Validation\Validator as v;

class FleetController extends ApiController
{
	/**
	 * @var FleetManager
	 */
	private $fleetManager;

	/**
	 * @var UserManager
	 */
	private $userManager;

	public function __init()
	{
		parent::__init();

		$this->fleetManager = $this->get('fleet.manager');
		$this->userManager = $this->get('fos_user.user_manager');
	}

	/**
	 * @Route("/api/v1/user/{user_id}/fleet")
	 * @Method("GET")
	 */
	public function getFleetByUserAction($user_id)
	{
		// list all fleets of this user id
		$fleet = $this->fleetManager->findByUserId($user_id);

		$this->denyAccessUnlessGranted(VoterEvent::VIEW, $fleet, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		return $this->renderJSON(
			['fleet' => $fleet],
			self::SUCCESS);
	}

	/**
	 * @Route("/api/v1/fleet/{fleet_id}")
	 * @Method("GET")
	 */
	public function getFleetByIdAction($fleet_id)
	{
		$fleet = $this->fleetManager->findOneById($fleet_id);

		$this->denyAccessUnlessGranted(VoterEvent::VIEW, $fleet, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		return $this->renderJSON(
			['fleet' => $fleet],
			self::SUCCESS);
	}

	/**
	 * @Route("/api/v1/fleet")
	 * @Method("POST")
	 */
	public function createFleetAction(Request $request)
	{
		$params = array();
		$content = $request->getContent();
		if (!empty($content)) {
			try {
				$params = json_decode($content, true);
			} catch (Exception $e) {
				throw new HttpException(400, "Bad Parameters");
			}
		}

		try {
			v::alnum("_-.")->noWhitespace()->length(3, 32)->assert($params['name']);
		} catch (NestedValidationException $exception) {
			throw new HttpException(400, $exception->getFullMessage());
		}

		$fleet = $this->fleetManager->create();

		$params['user'] = $this->userManager->findUserBy(['id' => $params['user_id']]);
		if (!$params['user'] instanceof User)
			throw new HttpException(400, "User not found");

		$this->fleetManager->setFromParams($fleet, $params);

		// make sure this creating user is granted access to do it
		$this->denyAccessUnlessGranted(VoterEvent::CREATE, $fleet, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		// update / save to DB
		$this->fleetManager->update($fleet);

		if ($fleet) {
			return $this->renderJSON(
				['fleet' => $fleet],
				self::SUCCESS_CREATED);
		} else {
			throw new HttpException(400, "Cannot create user");
		}
	}

	/**
	 * @Route("/api/v1/fleet") 
	 * @Method("DELETE")
	 */
	public function deleteFleetAction(Request $request)
	{
		$params = array();
		$content = $request->getContent();
		if (!empty($content)) {
			try {
				$params = json_decode($content, true); // 2nd param to get as array
			} catch (Exception $e) {
				throw new HttpException(400, "Bad Parameters");
			}
		}

		try {
			v::numeric()->assert($params['id']);
		} catch (NestedValidationException $exception) {
			throw new HttpException(400, $exception->getFullMessage());
		}

		// get fleet
		$fleet = $this->fleetManager->findOneById($params);

		// make sure this user is granted access to do it
		$this->denyAccessUnlessGranted(VoterEvent::DELETE, $fleet, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		// delete from DB
		$this->fleetManager->delete($fleet);

		if (!$fleet->getId()) {
			return $this->renderJSON(
				["result" => true],
				self::SUCCESS_DELETED);
		} else {
			throw new HttpException(400, "Cannot delete fleet");
		}
	}
}
