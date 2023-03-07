<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MongoDB\Driver\Manager;

class Lote extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'manzana_id', 'user_id'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manzana(): BelongsTo
    {
        return $this->belongsTo(Manzana::class);
    }

    public function balances(): HasOne
    {
        return $this->hasOne(Balances::class);
    }

    public function promesas(): HasOne
    {
        return $this->hasOne(Promesas::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pagos::class);
    }

}
