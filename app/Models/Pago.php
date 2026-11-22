<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'reserva_id',
        'proveedor',
        'metodo',
        'monto_original',
        'moneda_original',
        'monto',
        'moneda',
        'tipo_cambio',
        'estado',
        'culqi_cargo_id',
        'culqi_orden_id',
        'clave_idempotencia',
        'verificado_en',
    ];

    protected function casts(): array
    {
        return [
            'monto_original' => 'decimal:2',
            'monto' => 'decimal:2',
            'tipo_cambio' => 'decimal:8',
            'verificado_en' => 'datetime',
        ];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }
}
