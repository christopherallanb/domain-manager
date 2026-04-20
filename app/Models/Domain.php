<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'registrar',
        'expiration_date',
        'annual_cost',
        'notes',
        'auto_renew',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'annual_cost' => 'decimal:2',
        'auto_renew' => 'boolean',
    ];

    // Scope for upcoming / expired
    public function scopeExpiringWithin($query, int $days)
    {
        return $query->whereBetween('expiration_date', [now(), now()->addDays($days)]);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiration_date', '<', now());
    }
}
