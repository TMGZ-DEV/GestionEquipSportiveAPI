<?php
namespace App\Models\Enums;

enum StatutJoueur: string {
    case ACTIF = 'Actif';
    case BLESSE = 'Blessé';
    case SUSPENDU = 'Suspendu';
    case ABSENT = 'Absent';

    /**
     * Retourne le libellé du Statut d'un Joueur
     */
    public function label(): string {
        return match($this) {
            self::ACTIF => 'Actif',
            self::BLESSE => 'Blessé',
            self::SUSPENDU => 'Suspendu',
            self::ABSENT => 'Absent',
        };
    }
}

?>