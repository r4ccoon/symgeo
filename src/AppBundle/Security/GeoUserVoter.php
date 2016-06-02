<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GeoUserVoter extends Voter
{
	const EDIT = 'edit';
	const CREATE = 'create';

	protected function supports($attribute, $subject)
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, array(self::CREATE, self::EDIT))) {
			return false;
		}

		// only vote on User object. or create geo_user
		if (!$subject instanceof \FOS\UserBundle\Model\User
			|| $subject != 'geo_user'
		) {
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

		if ($subject instanceof \FOS\UserBundle\Model\User) {
			if ($attribute == self::CREATE) {
				if ($user->isGranted("ROLE_ADMIN")) {
					return true;
				} else if ($user->isGranted("ROLE_FLEET_MANAGER")) {
					// manager can add a driver
					if ($subject->hasRole("ROLE_DRIVER")) {
						return true;
					}
				}
			}
		}

		switch ($attribute) {
			case self::CREATE:
				return $this->canView($post, $user);
			case self::EDIT:
				return $this->canEdit($post, $user);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canView(Post $post, User $user)
	{
		// if they can edit, they can view
		if ($this->canEdit($post, $user)) {
			return true;
		}

		// the Post object could have, for example, a method isPrivate()
		// that checks a boolean $private property
		return !$post->isPrivate();
	}

	private function canEdit(Post $post, User $user)
	{
		// this assumes that the data object has a getOwner() method
		// to get the entity of the user who owns this data object
		return $user === $post->getOwner();
	}
}
