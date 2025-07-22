<?php

namespace App\Enums;

enum ContractType: string
{
    case PERMANENT = 'permanent';
    case TEMPORARY = 'temporary';
    case FREELANCE = 'freelance';
    case UNEMPLOYED = 'unemployed';
    
    public function label(): string
    {
        return match($this) {
            self::PERMANENT => 'Contrato Indefinido',
            self::TEMPORARY => 'Contrato Temporal',
            self::FREELANCE => 'Autónomo',
            self::UNEMPLOYED => 'Sin empleo',
        };
    }
}