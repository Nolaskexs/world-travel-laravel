<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destino extends Model
{
    protected $fillable = [
        'nombre',
        'pais',
        'descripcion',
        'imagen',
        'codigo_iata',
        'aeropuerto',
    ];

    public function vuelosOrigen(): HasMany
    {
        return $this->hasMany(Vuelo::class, 'origen_id');
    }

    public function vuelosDestino(): HasMany
    {
        return $this->hasMany(Vuelo::class, 'destino_id');
    }

    /**
     * Paquetes turísticos asociados a este destino (por coincidencia de nombre).
     */
    public function paquetes(): HasMany
    {
        return $this->hasMany(Paquete::class, 'destino', 'nombre');
    }
}
