<?php

namespace App\Security\Voter;

use App\Entity\Music;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MusicVoter extends Voter
{
    // Actions que ce Voter va vérifier
    const CREATE = 'create';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const VIEW = 'view';

    protected function supports(string $attribute, $subject): bool
    {
        // Vérifie que l’action demandée et l’objet sont bien ceux que nous traitons
        return in_array($attribute, [self::CREATE, self::EDIT, self::DELETE, self::VIEW])
            && $subject instanceof Music;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false; // Refuse l'accès si l'utilisateur n'est pas connecté
        }

        /** @var Music $music */
        $music = $subject;

        switch ($attribute) {
            case self::CREATE:
                // Autorise les artistes et beatmakers à créer de la musique
                return in_array('ROLE_ARTIST', $user->getRoles()) || in_array('ROLE_BEATMAKER', $user->getRoles());
                
            case self::EDIT:
            case self::DELETE:
                // Autorise uniquement le propriétaire à modifier ou supprimer
                return $user === $music->getBeatmaker() || $user === $music->getArtist();

            case self::VIEW:
                // Tous les utilisateurs peuvent voir la musique
                return true;
        }

        return false;
    }
}
