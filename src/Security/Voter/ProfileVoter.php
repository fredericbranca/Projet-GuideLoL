<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProfileVoter extends Voter
{
    public const USER_EDIT_PSEUDO = 'pseudo_edit';

    protected function supports(string $attribute, mixed $guide): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::USER_EDIT_PSEUDO])
            && $guide instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, mixed $guide, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // si l'utilisateur est anonyme, ne pas lui accorder l'accès
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (vérifie les conditions et retourne vrai pour accorder la permission) ...
        switch ($attribute) {
            case self::USER_EDIT_PSEUDO:
                // on vérifie si on peut éditer le pseudo
                return $this->canEditPseudo($user);
        }

        return false;
    }

    private function canEditPseudo(User $user) {
        // L'Utilisateur peut changer son Pseudo
        return $user->isVerified();
    }
}
