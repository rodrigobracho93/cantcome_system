<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Almuerzo extends Model
{
    protected $fillable = [
        'customer_id',
        'fecha',
        'entregado',
        'entregado_at',
        'user_id',
        'observacion',
    ];

    protected $casts = [
        'fecha' => 'date',
        'entregado' => 'boolean',
        'entregado_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
