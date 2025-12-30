<?php

namespace App\Models;

use App\Enums\TransactionSituation;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'due_date',
        'name',
        'description',
        'category_id',
        'amount',
        'situation',
        'payment_date',
        'user_id',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'type' => TransactionType::class,
        'situation' => TransactionSituation::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, TransactionType $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySituation($query, TransactionSituation $situation)
    {
        return $query->where('situation', $situation);
    }

    public function scopePaid($query)
    {
        return $query->where('situation', TransactionSituation::PAGO);
    }

    public function scopePending($query)
    {
        return $query->where('situation', TransactionSituation::A_PAGAR);
    }

    public function scopeOverdue($query)
    {
        return $query->where('situation', TransactionSituation::A_PAGAR)
                    ->where('due_date', '<', now()->toDateString());
    }
}