<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoAdicional extends Model
{
    use HasFactory;

    protected $table = 'cargos_adicionales';

    protected $fillable = [
        'descripcion',
        'total',
        'user_id',
    ];

    /**
     * Get the user that owns the cargo adicional.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pagos for the cargo adicional.
     */
    public function pagos()
    {
        return $this->hasMany(PagoCargoAdicional::class);
    }
}
