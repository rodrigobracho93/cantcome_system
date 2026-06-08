<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['first_name', 'last_name', 'document', 'company', 'phone', 'email'];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
