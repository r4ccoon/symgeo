<?php
namespace AppBundle\Security;

use AppBundle\Entity\Company;
use AppBundle\VoterEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CompanyVoter extends Voter
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

		// only vote on company object.
		if (is_array($subject) && isset($subject[0]) && $subject[0] instanceof Company) {
			return true;
		} else if ($subject instanceof Company) {
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

		return false;
	}
}
