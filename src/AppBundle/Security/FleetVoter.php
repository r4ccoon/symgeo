<?php
namespace AppBundle\Security;

use AppBundle\AppBundle;
use AppBundle\Entity\Fleet;
use AppBundle\Entity\User;
use AppBundle\VoterEvent;
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
			VoterEvent::CREATE,
			VoterEvent::EDIT,
			VoterEvent::DELETE,
			VoterEvent::VIEW))
		) {
			return false;
		}

		// only vote on fleet object.
		if (is_array($subject) && isset($subject[0]) && $subject[0] instanceof Fleet) {
			return true;
		} else if ($subject instanceof Fleet) {
			return true;
		}

		return false;
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
			$user = $token->getUser();

			// manager can see all their own fleets
			if (is_array($subject) && $attribute == VoterEvent::VIEW) {
				if (isset($subject[0]) && $subject[0]->user == $user) {
					return true;
				}

				return false;
			} else
				// manager can create/view/edit/delete its own fleet
				if ($subject->user == $user) {
					return true;
				}
		}

		return false;
	}
}
