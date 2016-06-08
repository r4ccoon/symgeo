<?php
namespace AppBundle\Security;

use AppBundle\AppBundle;
use AppBundle\Entity\Fleet;
use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FleetVoter extends Voter
{
	protected $decisionManager;

	public function __construct(AccessDecisionManagerInterface $decisionManager)
	{
		$this->decisionManager = $decisionManager;
	}

	protected function supports($attribute, $subject)
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, array(
			self::CREATE,
			self::EDIT,
			self::DELETE,
			self::VIEW))
		) {
			return false;
		}

		// only vote on User object. or create geo_user
		if (!$subject instanceof Fleet) {
			return false;
		}

		return true;
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{
		// admin, manager can create and can see
		if (
		$this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN'))
		) {
			return true;
		}

		if (
		$this->decisionManager->decide($token, array('ROLE_MANAGER'))
		) {
			// manager can create/view/edit/delete its own fleet
			$user = $token->getUser();
			if ($subject->user == $user) {
				return true;
			}
		}

		return false;
	}
}
