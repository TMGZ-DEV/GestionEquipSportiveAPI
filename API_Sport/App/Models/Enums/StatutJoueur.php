<?php
namespace App\Models\Enums;

enum StatutJoueur: string
{
    case ACTIF = 'Actif';
    case BLESSE = 'Blessé';
    case SUSPENDU = 'Suspendu';
    case ABSENT = 'Absent';

    public function label(): string
    {
        return match($this) {
            self::ACTIF => 'Actif',
            self::BLESSE => 'Blessé',
            self::SUSPENDU => 'Suspendu',
            self::ABSENT => 'Absent',
        };
    }
    
    public function badge(): string
    {
        return match($this) {
            self::ACTIF => 'success',
            self::BLESSE => 'warning',
            self::SUSPENDU => 'danger',
            self::ABSENT => 'secondary',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ACTIF => '✔️',
            self::BLESSE => '🤕',
            self::SUSPENDU => '⛔',
            self::ABSENT => '🚫',
        };
    }

}