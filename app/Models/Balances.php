<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Balances extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lote_id', 'total', 'credito', 'plan_de_pagos', 'tiene_deuda'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }

    public function interes() : BelongsTo
    {
        return $this->belongsTo(Interes::class);
    }
}
