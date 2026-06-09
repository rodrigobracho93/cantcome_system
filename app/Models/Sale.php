<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sale extends Model
{
    protected $fillable = [
        'user_id', 'customer_id', 'payment_type',
        'subtotal', 'tax', 'total', 'status', 'notes', 'synced', 'synced_at', 'paid_at'
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function scopeUnpaid($q)
    {
        $q->where('payment_type', 'credito')->whereNull('paid_at')->where('status', '!=', 'anulado');
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_type === 'contado' || $this->paid_at !== null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function cajaMovimientos(): MorphMany
    {
        return $this->morphMany(CajaMovimiento::class, 'referencia');
    }
}
