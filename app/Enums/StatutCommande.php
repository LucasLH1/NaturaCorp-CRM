<?php

namespace App\Enums;

enum StatutCommande: string
{
    case EN_ATTENTE = 'en_attente';
    case VALIDEE = 'validee';
    case LIVREE = 'livree';

    public function label(): string
    {
        return match($this) {
            self::EN_ATTENTE => 'En attente',
            self::VALIDEE => 'Validée',
            self::LIVREE => 'Livrée',
        };
    }
}
