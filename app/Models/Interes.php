<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interes extends Model
{
    use HasFactory;

    protected $table = 'intereses';
    protected $fillable = [
        'interes',
        'created_at',
        'updated_at'
    ];

    public function balance(): BelongsTo
    {
        return $this->belongsTo(Balances::class);
    }

}
