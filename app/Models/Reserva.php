<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reserva extends Model
{
    protected $fillable = [
        'user_id',
        'paquete_id',
        'tipo_servicio',
        'vuelo_id',
        'alojamiento_id',
        'oferta_id',
        'fecha_viaje',
        'fecha_entrada',
        'fecha_salida',
        'noches',
        'moneda',
        'cotizacion',
        'num_pasajeros',
        'total',
        'detalle_servicio',
        'detalle_snapshot',
        'asientos',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_viaje' => 'date',
            'fecha_entrada' => 'date',
            'fecha_salida' => 'date',
            'noches' => 'integer',
            'cotizacion' => 'decimal:2',
            'num_pasajeros' => 'integer',
            'total' => 'decimal:2',
            'detalle_snapshot' => 'array',
        ];
    }

    /**
     * Cliente que realizó la reserva.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Paquete turístico asociado a la reserva.
     */
    public function paquete(): BelongsTo
    {
        return $this->belongsTo(Paquete::class);
    }

    /**
     * Vuelo reservado, cuando el servicio es independiente.
     */
    public function vuelo(): BelongsTo
    {
        return $this->belongsTo(Vuelo::class);
    }

    /**
     * Alojamiento reservado, cuando el servicio es independiente.
     */
    public function alojamiento(): BelongsTo
    {
        return $this->belongsTo(Alojamiento::class);
    }

    /**
     * Oferta reservada, cuando el servicio es independiente.
     */
    public function oferta(): BelongsTo
    {
        return $this->belongsTo(Oferta::class);
    }

    /**
     * Pasajeros incluidos en la reserva.
     */
    public function pasajeros(): HasMany
    {
        return $this->hasMany(ReservaPasajero::class);
    }
}
