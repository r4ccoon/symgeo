<?php
namespace ApiBundle\v1\Controller;

use AppBundle\Model\CompanyManager;
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

class CompanyController extends ApiController
{
	/**
	 * @var CompanyManager
	 */
	private $companyManager;

	/**
	 * @var UserManager
	 */
	private $userManager;

	public function __init()
	{
		parent::__init();

		$this->companyManager = $this->get('company.manager');
		$this->userManager = $this->get('fos_user.user_manager');
	}

	/**
	 * @Route("/api/v1/company/{company_id}")
	 * @Method("GET")
	 */
	public function getCompanyByIdAction($company_id)
	{
		$comp = $this->companyManager->findOneById($company_id);

		return $this->renderJSON(
			['company' => $comp],
			self::SUCCESS);
	}

	/**
	 * @Route("/api/v1/company")
	 * @Method("GET")
	 */
	public function getCompanyAction(Request $request)
	{
		$offset = $request->get('offset', 0);
		$limit = $request->get('limit', 10);
		$orderBy = $request->get('orderBy', 'name');

		try {
			if ($offset)
				v::numeric()->assert($offset);
			if ($limit)
				v::numeric()->assert($limit);
			if ($orderBy)
				v::alnum("_-")->noWhitespace()->assert($orderBy);

		} catch (NestedValidationException $exception) {
			throw new HttpException(400, $exception->getFullMessage());
		}

		$comp = $this->companyManager->findBy([], [$orderBy => 'asc'], $limit, $offset);

		return $this->renderJSON(
			['company' => $comp],
			self::SUCCESS);
	}

	/**
	 * @Route("/api/v1/company")
	 * @Method("POST")
	 */
	public function createCompanyAction(Request $request)
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
			v::notEmpty()->assert($params['owner']);
		} catch (NestedValidationException $exception) {
			throw new HttpException(400, $exception->getFullMessage());
		}

		$obj = $this->companyManager->create();

		$params['owner'] = $this->userManager->findUserBy(['username' => $params['owner']]);
		if (!$params['owner'] instanceof User)
			throw new HttpException(400, "User not found");

		$this->companyManager->setFromParams($obj, $params);

		// make sure this creating user is granted access to do it
		$this->denyAccessUnlessGranted(VoterEvent::CREATE, $obj, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		// update / save to DB
		$this->companyManager->update($obj);

		if ($obj) {
			return $this->renderJSON(
				['company' => $obj],
				self::SUCCESS_CREATED);
		} else {
			throw new HttpException(400, "Cannot create company");
		}
	}

	/**
	 * @Route("/api/v1/company")
	 * @Method("DELETE")
	 */
	public function deleteCompanyAction(Request $request)
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
		$c = $this->companyManager->findOneById($params);

		// make sure this user is granted access to do it
		$this->denyAccessUnlessGranted(VoterEvent::DELETE, $c, self::FAIL_NOT_AUTHORIZED_MESSAGE);

		// delete from DB
		$this->companyManager->delete($c);

		if (!$c->getId()) {
			return $this->renderJSON(
				["result" => true],
				self::SUCCESS_DELETED);
		} else {
			throw new HttpException(400, "Cannot delete company");
		}
	}
}
