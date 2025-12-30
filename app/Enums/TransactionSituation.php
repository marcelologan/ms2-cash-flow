<?php

namespace App\Enums;

enum TransactionSituation: string
{
    // Para SAÍDAS (Despesas)
    case A_PAGAR = 'a_pagar';
    case PAGO = 'pago';
    case VENCIDO = 'vencido';
    
    // Para ENTRADAS (Receitas)  
    case A_RECEBER = 'a_receber';
    case RECEBIDO = 'recebido';
    case ATRASADO = 'atrasado';

    public function label(): string
    {
        return match($this) {
            self::A_PAGAR => 'A Pagar',
            self::PAGO => 'Pago',
            self::VENCIDO => 'Vencido',
            self::A_RECEBER => 'A Receber',
            self::RECEBIDO => 'Recebido',
            self::ATRASADO => 'Atrasado',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::A_PAGAR => '⏳',
            self::PAGO => '✅',
            self::VENCIDO => '❌',
            self::A_RECEBER => '⏳',
            self::RECEBIDO => '✅',
            self::ATRASADO => '⚠️',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::A_PAGAR => 'yellow',
            self::PAGO => 'green',
            self::VENCIDO => 'red',
            self::A_RECEBER => 'blue',
            self::RECEBIDO => 'green',
            self::ATRASADO => 'orange',
        };
    }

    public function badge(): string
    {
        return match($this) {
            self::A_PAGAR => 'bg-yellow-100 text-yellow-800',
            self::PAGO => 'bg-green-100 text-green-800',
            self::VENCIDO => 'bg-red-100 text-red-800',
            self::A_RECEBER => 'bg-blue-100 text-blue-800',
            self::RECEBIDO => 'bg-green-100 text-green-800',
            self::ATRASADO => 'bg-orange-100 text-orange-800',
        };
    }

    /**
     * Retorna situações válidas para um tipo de transação
     */
    public static function forType(TransactionType $type): array
    {
        return match($type) {
            TransactionType::SAIDA => [
                self::A_PAGAR->value => self::A_PAGAR->label(),
                self::PAGO->value => self::PAGO->label(),
                self::VENCIDO->value => self::VENCIDO->label(),
            ],
            TransactionType::ENTRADA => [
                self::A_RECEBER->value => self::A_RECEBER->label(),
                self::RECEBIDO->value => self::RECEBIDO->label(),
                self::ATRASADO->value => self::ATRASADO->label(),
            ],
        };
    }

    /**
     * Verifica se a situação requer data de pagamento
     */
    public function requiresPaymentDate(): bool
    {
        return in_array($this, [self::PAGO, self::RECEBIDO]);
    }

    /**
     * Verifica se é uma situação "paga/recebida"
     */
    public function isPaid(): bool
    {
        return in_array($this, [self::PAGO, self::RECEBIDO]);
    }

    /**
     * Verifica se é uma situação "pendente"
     */
    public function isPending(): bool
    {
        return in_array($this, [self::A_PAGAR, self::A_RECEBER]);
    }

    /**
     * Verifica se é uma situação "atrasada"
     */
    public function isOverdue(): bool
    {
        return in_array($this, [self::VENCIDO, self::ATRASADO]);
    }

    /**
     * Todas as opções disponíveis
     */
    public static function options(): array
    {
        return [
            self::A_PAGAR->value => self::A_PAGAR->label(),
            self::PAGO->value => self::PAGO->label(),
            self::VENCIDO->value => self::VENCIDO->label(),
            self::A_RECEBER->value => self::A_RECEBER->label(),
            self::RECEBIDO->value => self::RECEBIDO->label(),
            self::ATRASADO->value => self::ATRASADO->label(),
        ];
    }
}