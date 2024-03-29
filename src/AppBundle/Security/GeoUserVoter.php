<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\VoterEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GeoUserVoter extends Voter
{
	protected $decisionManager;

	public function __construct(AccessDecisionManagerInterface $decisionManager)
	{
		$this->decisionManager = $decisionManager;
	}

	protected function supports($attribute, $subject)
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute,
			array(
				VoterEvent::CREATE,
				VoterEvent::EDIT,
				VoterEvent::DELETE))
		) {
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
		if (!$subject instanceof \FOS\UserBundle\Model\User) {
			return false;
		}

		// admin can create normal user
		if (
		$this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN'))
		) {
			if ($subject->hasRole("ROLE_DRIVER") ||
				$subject->hasRole("ROLE_MANAGER") ||
				$subject->hasRole("ROLE_USER")
			)
				return true;
		}

		return false;
	}
}
