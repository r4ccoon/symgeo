<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use AppBundle\VoterEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PositionVoter extends Voter
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
				VoterEvent::CREATE_POSITION,
				VoterEvent::VIEW_POSITION,
				VoterEvent::VIEW_POSITION_RADIUS))
		) {
			return false;
		}

		return true;
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{
		if ($attribute == VoterEvent::VIEW_POSITION) {
			if (
			$this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_MANAGER'))
			) {
				return true;
			}
		}

		$roles = $token->getRoles();

		if ($attribute == VoterEvent::CREATE_POSITION) {
			if (
			$this->decisionManager->decide($token, array('ROLE_DRIVER'))
			) {
				return true;
			}
		}

		return false;
	}
}
