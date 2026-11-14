<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VueloEscala extends Model
{
    protected $fillable = [
        'vuelo_id', 'orden', 'numero_vuelo', 'aerolinea_operadora',
        'origen_destino_id', 'llegada_destino_id', 'hora_salida',
        'hora_llegada', 'duracion_tramo_minutos', 'tiempo_espera_minutos', 'cambio_avion',
    ];

    protected $casts = [
        'hora_salida' => 'datetime',
        'hora_llegada' => 'datetime',
        'cambio_avion' => 'boolean',
    ];

    public function origen(): BelongsTo
    {
        return $this->belongsTo(Destino::class, 'origen_destino_id');
    }

    public function llegada(): BelongsTo
    {
        return $this->belongsTo(Destino::class, 'llegada_destino_id');
    }

    public function vuelo(): BelongsTo
    {
        return $this->belongsTo(Vuelo::class);
    }
}
