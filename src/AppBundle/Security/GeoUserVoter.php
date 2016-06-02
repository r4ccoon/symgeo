<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GeoUserVoter extends Voter
{
	const EDIT = 'edit';
	const CREATE = 'create';
	protected $decisionManager;

	public function __construct(AccessDecisionManagerInterface $decisionManager)
	{
		$this->decisionManager = $decisionManager;
	}

	protected function supports($attribute, $subject)
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, array(self::CREATE, self::EDIT))) {
			return false;
		}

		// only vote on User object. or create geo_user
		if (!$subject instanceof \FOS\UserBundle\Model\User) {
			return false;
		}

		return true;
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{
		$user = $token->getUser();

		if (!$subject instanceof \FOS\UserBundle\Model\User) {
			// the user must be logged in; if not, deny access
			return false;
		}

		if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
			return true;
		}

		if ($subject instanceof \FOS\UserBundle\Model\User) {
			if ($attribute == self::CREATE) {
				if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
					return true;
				} else if ($this->decisionManager->decide($token, array('ROLE_FLEET_MANAGER'))) {
					// manager can add a driver
					if ($subject->hasRole("ROLE_DRIVER")) {
						return true;
					}
				}

				return false;
			}
		}

		throw new \LogicException('This code should not be reached!');
	}
}
