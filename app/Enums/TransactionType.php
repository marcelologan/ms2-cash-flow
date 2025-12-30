<?php

namespace App\Enums;

enum TransactionType: string
{
    case ENTRADA = 'entrada';
    case SAIDA = 'saida';

    public function label(): string
    {
        return match($this) {
            self::ENTRADA => 'Entrada',
            self::SAIDA => 'Saída',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ENTRADA => 'green',
            self::SAIDA => 'red',
        };
    }

    public static function options(): array
    {
        return [
            self::ENTRADA->value => self::ENTRADA->label(),
            self::SAIDA->value => self::SAIDA->label(),
        ];
    }
}