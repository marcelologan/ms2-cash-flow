<?php

namespace App\Http\Controllers;

use App\Enums\TransactionSituation;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Estatísticas Gerais
        $totalTransactions = Transaction::where('user_id', $userId)->count();

        // Entradas
        $totalEntradas = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::ENTRADA->value)
            ->sum('amount');

        $entradasRecebidas = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::ENTRADA->value)
            ->where('situation', TransactionSituation::RECEBIDO->value)
            ->sum('amount');

        $entradasPendentes = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::ENTRADA->value)
            ->whereIn('situation', [
                TransactionSituation::A_RECEBER->value,
                TransactionSituation::ATRASADO->value
            ])
            ->sum('amount');

        // Saídas
        $totalSaidas = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::SAIDA->value)
            ->sum('amount');

        $saidasPagas = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::SAIDA->value)
            ->where('situation', TransactionSituation::PAGO->value)
            ->sum('amount');

        $saidasPendentes = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::SAIDA->value)
            ->whereIn('situation', [
                TransactionSituation::A_PAGAR->value,
                TransactionSituation::VENCIDO->value
            ])
            ->sum('amount');

        // Saldo
        $saldoTotal = $totalEntradas - $totalSaidas;
        $saldoRealizado = $entradasRecebidas - $saidasPagas;
        $saldoPendente = $entradasPendentes - $saidasPendentes;

        // Transações por Situação (com tratamento de erro)
        // Transações por Situação (com tratamento de erro)
        $transacoesPorSituacao = Transaction::where('user_id', $userId)
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->situation->label();
            })
            ->map(function ($group) {
                return $group->count();
            });

        // Transações Vencidas/Atrasadas (com filtro de situações válidas)
        $validSituations = [
            TransactionSituation::A_PAGAR->value,
            TransactionSituation::A_RECEBER->value,
            TransactionSituation::VENCIDO->value,
            TransactionSituation::ATRASADO->value
        ];

        $transacoesVencidas = Transaction::where('user_id', $userId)
            ->where('due_date', '<', $now->toDateString())
            ->whereIn('situation', $validSituations)
            ->with('category')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Próximas Transações (próximos 7 dias)
        $proximasTransacoes = Transaction::where('user_id', $userId)
            ->whereBetween('due_date', [$now->toDateString(), $now->copy()->addDays(7)->toDateString()])
            ->whereIn('situation', [
                TransactionSituation::A_PAGAR->value,
                TransactionSituation::A_RECEBER->value
            ])
            ->with('category')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Transações do Mês Atual
        $transacoesMesAtual = Transaction::where('user_id', $userId)
            ->whereBetween('due_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->count();

        $entradasMesAtual = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::ENTRADA->value)
            ->whereBetween('due_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('amount');

        $saidasMesAtual = Transaction::where('user_id', $userId)
            ->where('type', TransactionType::SAIDA->value)
            ->whereBetween('due_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('amount');

        // Categorias Mais Usadas
        $categoriasMaisUsadas = Transaction::where('user_id', $userId)
            ->with('category')
            ->selectRaw('category_id, COUNT(*) as total, SUM(amount) as valor_total')
            ->groupBy('category_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Evolução Mensal (últimos 6 meses)
        $evolucaoMensal = collect();
        for ($i = 5; $i >= 0; $i--) {
            $mesReferencia = $now->copy()->subMonths($i);
            $inicioMes = $mesReferencia->copy()->startOfMonth();
            $fimMes = $mesReferencia->copy()->endOfMonth();

            $entradas = Transaction::where('user_id', $userId)
                ->where('type', TransactionType::ENTRADA->value)
                ->whereBetween('due_date', [$inicioMes->toDateString(), $fimMes->toDateString()])
                ->sum('amount');

            $saidas = Transaction::where('user_id', $userId)
                ->where('type', TransactionType::SAIDA->value)
                ->whereBetween('due_date', [$inicioMes->toDateString(), $fimMes->toDateString()])
                ->sum('amount');

            $evolucaoMensal->push([
                'mes' => $mesReferencia->format('M/Y'),
                'entradas' => $entradas,
                'saidas' => $saidas,
                'saldo' => $entradas - $saidas
            ]);
        }

        return view('dashboard', compact(
            'totalTransactions',
            'totalEntradas',
            'totalSaidas',
            'entradasRecebidas',
            'entradasPendentes',
            'saidasPagas',
            'saidasPendentes',
            'saldoTotal',
            'saldoRealizado',
            'saldoPendente',
            'transacoesPorSituacao',
            'transacoesVencidas',
            'proximasTransacoes',
            'transacoesMesAtual',
            'entradasMesAtual',
            'saidasMesAtual',
            'categoriasMaisUsadas',
            'evolucaoMensal'
        ));
    }
}
