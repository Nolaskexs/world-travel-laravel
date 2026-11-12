<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItinerarioDestino extends Model
{
    protected $table = 'itinerario_destinos';

    protected $fillable = [
        'itinerario_id',
        'orden',
        'pais',
        'ciudad',
        'fecha_llegada',
        'fecha_salida',
        'costo_estimado_usd',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'orden' => 'integer',
            'fecha_llegada' => 'date',
            'fecha_salida' => 'date',
            'costo_estimado_usd' => 'decimal:2',
        ];
    }

    // Cada destino pertenece a un itinerario.
    public function itinerario(): BelongsTo
    {
        return $this->belongsTo(Itinerario::class);
    }
}
