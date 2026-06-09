<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'stock', 'barcode',
        'is_active', 'created_by', 'price_status', 'price_approved_by', 'price_approved_at',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'price_approved_by');
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('price_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('price_status', 'pending');
    }

    public function isPricePending(): bool
    {
        return $this->price_status === 'pending';
    }

    public function isPriceApproved(): bool
    {
        return $this->price_status === 'approved';
    }
}
