<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RecruteurTypeVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return $attribute === 'ROLE_RECRUTEUR' && $subject === null;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $request = Request::createFromGlobals();
        $recruteurType = $token->getType();

        if ($recruteurType === 'recruteur' && $this->isRoleGranted($token, 'ROLE_RECRUTEUR') && $request->getPathInfo() === '/particulier') {
            return $attribute==='ROLE_RECRUTEUR';
        }

        if ($recruteurType === 'particulier' && $this->isRoleGranted($token, 'ROLE_RECRUTEUR') && $request->getPathInfo() === '/recruteur_accueil') {
            return false;
        }

        return true;
    }

    private function isRoleGranted(TokenInterface $token, string $role): bool
    {
        return in_array($role, $token->getRoleNames(), true);
    }
}
