<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mapear situações antigas para novas
        $mappings = [
            'pendente' => 'a_pagar',
            'paga' => 'pago',
            'vencida' => 'vencido',
            'recebida' => 'recebido',
            'a_receber' => 'a_receber',
            'atrasada' => 'atrasado',
        ];

        foreach ($mappings as $old => $new) {
            DB::table('transactions')
                ->where('situation', $old)
                ->update(['situation' => $new]);
        }

        // Para situações que não conseguimos mapear, definir como padrão baseado no tipo
        DB::statement("
            UPDATE transactions 
            SET situation = CASE 
                WHEN type = 'entrada' THEN 'a_receber'
                WHEN type = 'saida' THEN 'a_pagar'
                ELSE 'a_pagar'
            END
            WHERE situation NOT IN ('a_pagar', 'pago', 'vencido', 'a_receber', 'recebido', 'atrasado')
        ");
    }

    public function down()
    {
        // Não é necessário reverter
    }
};