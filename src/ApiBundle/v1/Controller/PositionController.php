<?php
namespace ApiBundle\v1\Controller;

use AppBundle\Model\DriverPositionManager;
use AppBundle\VoterEvent;
use FOS\UserBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Respect\Validation\Exceptions\NestedValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Respect\Validation\Validator as v;

class PositionController extends ApiController
{
	/**
	 * @var UserManager
	 */
	private $userManager;

	/**
	 * @var DriverPositionManager
	 */
	private $positionManager;

	public function __init()
	{
		parent::__init();

		$this->userManager = $this->get('fos_user.user_manager');
		$this->positionManager = $this->get('position.manager');
	}

	/**
	 * return position of a user
	 * @Route("/api/v1/position/{user_id}")
	 * @Method("GET")
	 * @param $user_id
	 * @param Request $request
	 * @return Response
	 */
	public function getPositionByUserIdAction($user_id, Request $request)
	{
		/**
		 * SEARCH:
		 * [timerange]: unix_timestamp (start), unix_timestamp (end)
		 */

		$positions = [];

		// only admin/manager can access this method
		$this->denyAccessUnlessGranted(VoterEvent::VIEW_POSITION, null, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		$range = $request->query->get('timerange');
		if (isset($range)) {
			$times = explode(",", $range);
			$start = $times[0];
			$end = $times[1];

			try {
				v::notBlank()->numeric()->assert($start);
				v::notBlank()->numeric()->assert($end);
			} catch (NestedValidationException $exception) {
				throw new HttpException(400, $exception->getFullMessage());
			}

			$positions = $this->positionManager->findByRange($start, $end, $user_id);
		} else
			$positions = $this->positionManager->findOneByUserId($user_id);


		return $this->renderJSON(
			['position' => $positions],
			self::SUCCESS);
	}

	/**
	 * more flexible api to get positions
	 * will return positions of all driver or a specific driver on a given radius
	 * @Route("/api/v1/position")
	 * @Method("GET")
	 * @param Request $request
	 * @return Response
	 */
	public function getPositionAction(Request $request)
	{
		/**
		 * SEARCH:
		 *
		 * from: <x,y>
		 * radius: <int(km)>
		 * [mode: "area"]
		 * [user_id: <int>]
		 * [timerange]: unix_timestamp (start), unix_timestamp (end)
		 *
		 */
		$radius = intval($request->query->get('radius'));
		$from = explode(',', $request->query->get('from'));

		try {
			v::notBlank()->numeric()->between(1, 5)->assert($radius);
			v::notBlank()->arrayType()->assert($from);
			v::notBlank()->floatVal()->assert($from[0]);
			v::notBlank()->floatVal()->assert($from[1]);
		} catch (NestedValidationException $exception) {
			throw new HttpException(400, $exception->getFullMessage());
		}

		$mode = $request->query->get('mode');
		if (!isset($mode)) {
			$mode = DriverPositionManager::AREA;
		}

		$user_id = $request->query->get('user_id');
		if ($user_id != null) {
			try {
				v::numeric()->assert($user_id);
			} catch (NestedValidationException $exception) {
				throw new HttpException(400, $exception->getFullMessage());
			}
		}

		$range = $request->query->get('timerange');
		$startTime = null;
		$endTime = null;
		if (isset($range)) {
			$times = explode(",", $range);
			$startTime = $times[0];
			$endTime = $times[1];

			try {
				v::notBlank()->numeric()->assert($startTime);
				v::notBlank()->numeric()->assert($endTime);
			} catch (NestedValidationException $exception) {
				throw new HttpException(400, $exception->getFullMessage());
			}
		}

		// all user can access this method
		// todo: decide what users can access driver positions
		//$this->denyAccessUnlessGranted(VoterEvent::VIEW_POSITION_RADIUS, null, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		$positions = $this->positionManager->findByRadius($mode, $from[0], $from[1], $radius, $startTime, $endTime, $user_id);

		return $this->renderJSON(
			['position' => $positions],
			self::SUCCESS);
	}

	/**
	 * @Route("/api/v1/position")
	 * @Method("POST")
	 * @param Request $request
	 * @return Response
	 */
	public function createPositionAction(Request $request)
	{
		// only driver can post position
		$this->denyAccessUnlessGranted(VoterEvent::CREATE_POSITION, null, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		/**
		 * required:
		 * lat lng
		 */
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
			v::notBlank()->floatVal()->assert($params['lat']);
			v::notBlank()->floatVal()->assert($params['lng']);
		} catch (NestedValidationException $exception) {
			throw new HttpException(400, $exception->getFullMessage());
		}

		$params['user'] = $this->getUser();

		$pos = $this->positionManager->createPosition($params);

		if ($pos) {
			return $this->renderJSON(
				['position' => $pos],
				self::SUCCESS_CREATED);
		} else {
			throw new HttpException(400, "Cannot create position");
		}
	}
}
