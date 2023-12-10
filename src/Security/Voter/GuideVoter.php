<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Guide;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class GuideVoter extends Voter
{
    public const GUIDE_EDIT = 'guide_edit';
    public const GUIDE_DELETE = 'guide_delete';
    public const GUIDE_NOTE = 'guide_note';

    protected function supports(string $attribute, mixed $guide): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::GUIDE_EDIT, self::GUIDE_DELETE, self::GUIDE_NOTE])
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
        if (null === $guide->getUser()) {
            return $this->canCreate($guide, $user);
        }

        // ... (vérifie les conditions et retourne vrai pour accorder la permission) ...
        switch ($attribute) {
            case self::GUIDE_EDIT:
                // on vérifie si on peut éditer
                return $this->canEdit($guide, $user);
            case self::GUIDE_DELETE:
                // on vérifie si on peut supprimer
                return $this->canDelete($guide, $user);
            case self::GUIDE_NOTE:
                // on vérifie s'il peut noter le guide
                return $this->canNote($guide, $user);
        }

        return false;
    }

    private function canCreate(Guide $guide, User $user)
    {
        // L'utilisateur peut créer un guide
        return true;
    }

    private function canEdit(Guide $guide, User $user)
    {
        // Le propriétaire du guide peut le modifier
        return $user === $guide->getUser();
    }

    private function canDelete(Guide $guide, User $user)
    {
        // Le propriétaire du guide peut le supprimer
        return $user === $guide->getUser();
    }

    private function canNote(Guide $guide, User $user)
    {
        // L'utilisateur ne peut noter son propre guide
        if ($user === $guide->getUser()) {
            throw new AccessDeniedException("Vous ne pouvez pas noter votre propre guide");
        }

        // Si un utilisateur a déjà voté sur un gudie return false
        $evaluations = $guide->getEvaluations();
        foreach ($evaluations as $evaluation) {
            if ($evaluation->getNotation() !== null) {
                if ($evaluation->getUser() === $user) {
                    return false;
                }
            }
        }

        // Sinon return true, il peut noter ce guide
        return true;
    }
}
