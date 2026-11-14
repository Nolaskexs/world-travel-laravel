<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vuelo extends Model
{
    protected $fillable = [
        'origen_id', 'destino_id', 'codigo_vuelo', 'aerolinea',
        'fecha_salida', 'fecha_llegada', 'duracion_minutos', 'dias_diferencia',
        'cantidad_escalas', 'incluye_articulo_personal', 'incluye_equipaje_mano',
        'incluye_equipaje_bodega', 'moneda', 'precio_adulto', 'precio_menor',
        'precio_infante', 'impuestos_tasas', 'precio_final', 'asientos_disponibles',
        'etiqueta', 'activo',
    ];

    protected $casts = [
        'fecha_salida' => 'datetime',
        'fecha_llegada' => 'datetime',
        'incluye_articulo_personal' => 'boolean',
        'incluye_equipaje_mano' => 'boolean',
        'incluye_equipaje_bodega' => 'boolean',
    ];

    public function origen(): BelongsTo
    {
        return $this->belongsTo(Destino::class, 'origen_id');
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(Destino::class, 'destino_id');
    }

    public function escalas(): HasMany
    {
        return $this->hasMany(VueloEscala::class)->orderBy('orden');
    }

    // Helper para mostrar "51h 45m" automáticamente en Blade: {{ $vuelo->duracion_formateada }}
    public function getDuracionFormateadaAttribute(): string
    {
        $horas = intdiv($this->duracion_minutos, 60);
        $minutos = $this->duracion_minutos % 60;
        return "{$horas}h " . str_pad((string) $minutos, 2, '0', STR_PAD_LEFT) . "m";
    }
}
