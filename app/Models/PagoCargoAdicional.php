<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCargoAdicional extends Model
{
    use HasFactory;

    protected $table = 'pagos_cargos_adicionales';

    protected $fillable = [
        'cargo_adicional_id',
        'total',
    ];

    /**
     * Get the cargo adicional that owns the pago.
     */
    public function cargoAdicional()
    {
        return $this->belongsTo(CargoAdicional::class);
    }
}
