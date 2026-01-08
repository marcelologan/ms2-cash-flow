<?php

namespace App\Http\Controllers;

use App\Enums\TransactionSituation;
use App\Enums\TransactionType;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('due_date', 'desc');

        // Filtros melhorados
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($cat) use ($search) {
                        $cat->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('situation')) {
            $query->where('situation', $request->situation);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('due_date', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('due_date', '<=', $request->data_fim);
        }

        $transactions = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // Totais para estatísticas
        $totalTransactions = Transaction::where('user_id', Auth::id())->count();
        $totalEntradas = Transaction::where('user_id', Auth::id())
            ->where('type', TransactionType::ENTRADA->value)->count();
        $totalSaidas = Transaction::where('user_id', Auth::id())
            ->where('type', TransactionType::SAIDA->value)->count();

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'totalTransactions',
            'totalEntradas',
            'totalSaidas'
        ));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransaction($request);
        $validated['user_id'] = Auth::id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transação criada com sucesso!');
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $transaction->load('category');
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $this->validateTransaction($request);
        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transação atualizada com sucesso!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transação excluída com sucesso!');
    }

    /**
     * Marcar transação como paga/recebida
     */
    public function markAsPaid(Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }

        $newSituation = $transaction->type === TransactionType::ENTRADA->value
            ? TransactionSituation::RECEBIDO->value
            : TransactionSituation::PAGO->value;

        $transaction->update([
            'situation' => $newSituation,
            'payment_date' => now()->format('Y-m-d')
        ]);

        $message = $transaction->type === TransactionType::ENTRADA->value
            ? 'Transação marcada como recebida!'
            : 'Transação marcada como paga!';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Buscar categorias por tipo via AJAX
     */
    public function getCategoriesByType($type)
    {
        $categories = Category::where('type', $type)
            ->where('is_active', true)  // Filtrar apenas ativas
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($categories);
    }

    /**
     * Buscar situações por tipo via AJAX
     */
    public function getSituationsByType($type)
    {
        try {
            $transactionType = TransactionType::from($type);
            $situations = TransactionSituation::forType($transactionType);

            return response()->json($situations);
        } catch (\ValueError $e) {
            return response()->json(['error' => 'Tipo inválido'], 400);
        }
    }

    /**
     * Validação centralizada de transações
     */
    private function validateTransaction(Request $request): array
    {
        $rules = [
            'type' => 'required|in:' . implode(',', array_column(TransactionType::cases(), 'value')),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'situation' => 'required|in:' . implode(',', array_column(TransactionSituation::cases(), 'value')),
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Validações específicas baseadas na situação
        $this->validateSituationLogic($validated);

        return $validated;
    }

    /**
     * Validar lógica de situação vs datas
     */
    private function validateSituationLogic(array $data): void
    {
        $situation = TransactionSituation::from($data['situation']);
        $dueDate = Carbon::parse($data['due_date']);
        $today = Carbon::today();

        // Validar se situação requer data de pagamento
        if ($situation->requiresPaymentDate() && empty($data['payment_date'])) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['payment_date' => ['Data de pagamento é obrigatória para esta situação.']]
            );
        }

        // Validar consistência entre data de vencimento e situação
        if ($situation->isPending() && $dueDate->lt($today)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['due_date' => ['Data de vencimento deve ser futura para situação pendente.']]
            );
        }

        if ($situation->isOverdue() && $dueDate->gte($today)) {
            throw new \Illuminate\Validation\ValidationException(
                validator([], []),
                ['due_date' => ['Data de vencimento deve ser passada para situação atrasada/vencida.']]
            );
        }
    }

    /**
     * Buscar todas as categorias do usuário via AJAX
     */
    public function getAllCategories()
    {
        $categories = Category::where('is_active', true)  // Remover user_id
            ->orderBy('name')
            ->get(['id', 'name', 'type']);

        return response()->json($categories);
    }

    /**
     * Buscar todas as situações via AJAX
     */
    public function getAllSituations()
    {
        $situations = TransactionSituation::options();
        return response()->json($situations);
    }

    // Adicionar este método ao final da classe TransactionController

    /**
     * Export transactions to PDF
     */
    public function exportPdf(Request $request)
    {
        // Aplicar os mesmos filtros da index
        $query = Transaction::with(['category'])
            ->where('user_id', Auth::id())
            ->orderBy('due_date', 'desc');

        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('situation')) {
            $query->where('situation', $request->situation);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('data_inicio')) {
            $query->where('due_date', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('due_date', '<=', $request->data_fim);
        }

        $transactions = $query->get();

        // Calcular totais
        $totalEntradas = $transactions->where('type', 'entrada')->sum('amount');
        $totalSaidas = $transactions->where('type', 'saida')->sum('amount');
        $saldo = $totalEntradas - $totalSaidas;

        // Dados para o PDF
        $data = [
            'transactions' => $transactions,
            'totalTransactions' => $transactions->count(),
            'totalEntradas' => $totalEntradas,
            'totalSaidas' => $totalSaidas,
            'saldo' => $saldo,
            'filters' => $request->all(),
            'generatedAt' => now()->format('d/m/Y H:i:s'),
            'user' => Auth::user() // ✅ CORREÇÃO: Auth::user()
        ];

        // Gerar PDF real
        $pdf = PDF::loadView('transactions.pdf', $data);
        return $pdf->download('relatorio-transacoes-' . now()->format('Y-m-d') . '.pdf');
    }
}
