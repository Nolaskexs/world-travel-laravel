<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Itinerario extends Model
{
    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'presupuesto_objetivo_usd',
        'moneda',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'presupuesto_objetivo_usd' => 'decimal:2',
        ];
    }

    // Un itinerario pertenece a un usuario.
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Un itinerario puede contener varios destinos.
    public function destinos(): HasMany
    {
        return $this->hasMany(ItinerarioDestino::class)
                    ->orderBy('orden');
    }
}
