<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Guide;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GuideVoter extends Voter
{
    public const GUIDE_EDIT = 'guide_edit';
    
    public const GUIDE_DELETE = 'guide_delete';

    protected function supports(string $attribute, mixed $guide): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::GUIDE_EDIT, self::GUIDE_DELETE])
            && $guide instanceof \App\Entity\Guide;
    }

    protected function voteOnAttribute(string $attribute, mixed $guide, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // si l'utilisateur est anonyme, ne pas lui accorder l'accès
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si le guide a un user
        if(null === $guide->getUser()) {
            return $this->canCreate($guide, $user);
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::GUIDE_EDIT:
                // on vérifie si on peut éditer
                return $this->canEdit($guide, $user);
            case self::GUIDE_DELETE:
                // on vérifie si on peut supprimer
                return $this->canDelete($guide, $user);
        }

        return false;
    }

    private function canCreate(Guide $guide, User $user) {
        // L'utilisateur peut créer un guide
        return true;
    }

    private function canEdit(Guide $guide, User $user) {
        // Le propriétaire du guide peut le modifier
        return $user === $guide->getUser();
    }

    private function canDelete(Guide $guide, User $user) {
        // Le propriétaire du guide peut le supprimer
        return $user === $guide->getUser();
    }
}
