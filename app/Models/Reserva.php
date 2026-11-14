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
        'fecha_viaje',
        'num_pasajeros',
        'total',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_viaje' => 'date',
            'num_pasajeros' => 'integer',
            'total' => 'decimal:2',
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
     * Pasajeros incluidos en la reserva.
     */
    public function pasajeros(): HasMany
    {
        return $this->hasMany(ReservaPasajero::class);
    }
}
