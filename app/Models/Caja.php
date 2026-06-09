<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caja extends Model
{
    protected $table = 'caja';

    protected $fillable = [
        'user_id', 'fecha_apertura', 'fecha_cierre',
        'monto_inicial', 'monto_final_esperado', 'monto_final_real',
        'diferencia', 'total_ingresos', 'total_egresos',
        'estado', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_apertura' => 'datetime',
            'fecha_cierre' => 'datetime',
            'monto_inicial' => 'decimal:2',
            'monto_final_esperado' => 'decimal:2',
            'monto_final_real' => 'decimal:2',
            'diferencia' => 'decimal:2',
            'total_ingresos' => 'decimal:2',
            'total_egresos' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(CajaMovimiento::class, 'caja_id');
    }
}