<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alojamiento extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'categoria',
        'ubicacion',
        'habitacion',
        'descripcion',
        'imagen',
        'imagen_tarjeta',
        'precio',
        'precio_anterior',
        'moneda_simbolo',
        'puntuacion',
        'estrellas',
        'servicios',
        'servicios_resumen',
        'palabras_clave',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'precio_anterior' => 'decimal:2',
            'puntuacion' => 'decimal:1',
            'servicios' => 'array',
            'activo' => 'boolean',
        ];
    }

    /**
     * Las rutas públicas usan el código legible del alojamiento.
     */
    public function getRouteKeyName(): string
    {
        return 'codigo';
    }

    /**
     * Reservas registradas para este alojamiento.
     */
    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
