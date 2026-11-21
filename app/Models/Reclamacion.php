<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reclamacion extends Model
{
    use HasFactory;

    protected $table = 'reclamaciones';

    protected $fillable = [
        'codigo',
        'user_id',
        'tipo_persona',
        'nombre',
        'tipo_documento',
        'documento',
        'email',
        'telefono',
        'pais',
        'tipo_bien',
        'producto',
        'reserva',
        'moneda',
        'monto',
        'tipo',
        'motivo',
        'detalle',
        'pedido',
        'formato_respuesta',
        'estado',
        'respuesta',
        'respondido_por',
        'fecha_respuesta',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_respuesta' => 'datetime',
    ];

    /**
     * Relación con el usuario (si estaba autenticado al registrar).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Administrador que redactó la respuesta.
     */
    public function respondidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    /**
     * Generador automático del correlativo oficial.
     */
    public static function generarCodigo(): string
    {
        $anio = date('Y');
        $ultimoId = static::whereYear('created_at', $anio)->max('id') ?? 0;
        $correlativo = str_pad((string) ($ultimoId + 1), 4, '0', STR_PAD_LEFT);

        return "REC-{$anio}-{$correlativo}";
    }

    /**
     * Nombre amigable del motivo.
     */
    public function getMotivoLabelAttribute(): string
    {
        return match ($this->motivo) {
            'reclamo_vuelo' => 'Disconformidad con el vuelo, paquete o servicio',
            'queja_atencion' => 'Malestar en la atención al cliente',
            'facturacion_cobro' => 'Problemas con facturación o cobros',
            'reembolso_cancelacion' => 'Reembolsos y cancelaciones',
            default => ucfirst(str_replace('_', ' ', $this->motivo ?? 'General')),
        };
    }

    /**
     * Etiqueta amigable del estado.
     */
    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En proceso',
            'atendido' => 'Atendido',
            'archivado' => 'Archivado',
            default => ucfirst($this->estado),
        };
    }
}
