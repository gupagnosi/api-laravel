<?php

namespace App\Enums;

enum Status: string
{
    case EM_ESTOQUE = 'em estoque';
    case EM_REPOSICAO = 'em reposição';
    case EM_FALTA = 'em falta';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
