<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paquete extends Model
{
    protected $fillable = [
        'destino_id', 'titulo', 'destino', 'pais', 'continente', 'descripcion', 'duracion_dias',
        'precio', 'moneda', 'imagen_url', 'incluye_vuelo', 'incluye_hotel',
        'incluye_tour', 'destacado', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'duracion_dias' => 'integer',
            'incluye_vuelo' => 'boolean',
            'incluye_hotel' => 'boolean',
            'incluye_tour' => 'boolean',
            'destacado' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    public function destinoRel(): BelongsTo
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
