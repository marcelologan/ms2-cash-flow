<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ ROTA DE EXPORT PDF (ANTES DE TODAS AS OUTRAS DE TRANSACTIONS)
    Route::get('/transactions/export-pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export-pdf');

    // Rotas específicas de transações (ANTES da resource)
    Route::get('/transactions/categories/all', [TransactionController::class, 'getAllCategories'])
        ->name('transactions.categories-all');
    Route::get('/transactions/situations/all', [TransactionController::class, 'getAllSituations'])
        ->name('transactions.situations-all');
    Route::get('/transactions/categories/{type}', [TransactionController::class, 'getCategoriesByType'])
        ->name('transactions.categories-by-type');
    Route::get('/transactions/situations/{type}', [TransactionController::class, 'getSituationsByType'])
        ->name('transactions.situations-by-type');
    Route::patch('/transactions/{transaction}/mark-as-paid', [TransactionController::class, 'markAsPaid'])
        ->name('transactions.mark-as-paid');

    // Rotas de Transações (resource por último)
    Route::resource('transactions', TransactionController::class);
});

require __DIR__ . '/auth.php';
