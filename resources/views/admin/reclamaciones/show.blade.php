@extends('admin')

@section('title', 'Detalle de Reclamación ' . $reclamacion->codigo)
@section('active', 'reclamaciones')

@push('styles')
<style>
    .rec-detail-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 25px 70px;
        color: var(--ink, #16354a);
    }

    .rec-top-nav {
        margin-bottom: 20px;
    }

    .rec-back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--slate, #64748b);
        text-decoration: none;
        font-weight: 600;
        font-size: 13.5px;
    }

    .rec-back-link:hover {
        color: #0f172a;
    }

    /* Cabecera del reclamo */
    .rec-sheet-header {
        background: #ffffff;
        border: 1px solid #dae5ec;
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    .rec-sheet-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .rec-sheet-title h1 {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .rec-sheet-meta {
        font-size: 13px;
        color: #64748b;
        margin-top: 4px;
    }

    .rec-grid {
        display: grid;
        grid-template-columns: 1.6fr 1.2fr;
        gap: 24px;
        align-items: start;
    }

    /* Tarjetas de contenido */
    .rec-card {
        background: #ffffff;
        border: 1px solid #dae5ec;
        border-radius: 14px;
        padding: 22px 24px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .rec-card h2 {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rec-dl {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 20px;
        font-size: 13.5px;
    }

    .rec-dl dt {
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 2px;
    }

    .rec-dl dd {
        margin: 0;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }

    .rec-box-text {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 13.5px;
        line-height: 1.6;
        color: #1e293b;
        white-space: pre-line;
        margin-top: 6px;
    }

    /* Badges */
    .rec-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .rec-badge--reclamo { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .rec-badge--queja { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
    .rec-badge--pendiente { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .rec-badge--en_proceso { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .rec-badge--atendido { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .rec-badge--archivado { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    /* Formulario de gestión */
    .rec-form-group {
        margin-bottom: 16px;
    }

    .rec-form-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 6px;
    }

    .rec-form-select, .rec-form-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13.5px;
        background: #ffffff;
        color: #1e293b;
        outline: none;
        box-sizing: border-box;
    }

    .rec-form-select:focus, .rec-form-textarea:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
    }

    .rec-btn-save {
        width: 100%;
        background: #0f172a;
        color: #ffffff;
        padding: 12px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .rec-btn-save:hover {
        background: #1e293b;
    }

    .rec-alert-success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 14px;
    }

    .rec-legal-notice {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        padding: 12px 14px;
        border-radius: 8px;
        margin-top: 15px;
    }

    @media (max-width: 900px) {
        .rec-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="rec-detail-page">

    <div class="rec-top-nav">
        <a href="{{ route('admin.reclamaciones.index') }}" class="rec-back-link">
            &larr; Volver al listado de reclamaciones
        </a>
    </div>

    @if(session('success'))
        <div class="rec-alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- CABECERA DE LA HOJA DE RECLAMACIÓN -->
    <div class="rec-sheet-header">
        <div>
            <div class="rec-sheet-title">
                <h1>{{ $reclamacion->codigo }}</h1>
                <span class="rec-badge rec-badge--{{ $reclamacion->tipo }}">
                    {{ ucfirst($reclamacion->tipo) }}
                </span>
                <span class="rec-badge rec-badge--{{ $reclamacion->estado }}">
                    {{ $reclamacion->estado_label }}
                </span>
            </div>
            <div class="rec-sheet-meta">
                Registrado el <strong>{{ $reclamacion->created_at->format('d/m/Y \a \l\a\s H:i') }}</strong>
                ({{ $reclamacion->created_at->diffForHumans() }})
            </div>
        </div>
        <div>
            <span style="font-size: 12.5px; font-weight: 700; color: #475569; background: #f1f5f9; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                Razón Social: World Travel Perú S.A.C. (RUC 20609876543)
            </span>
        </div>
    </div>

    <div class="rec-grid">

        <!-- COLUMNA IZQUIERDA: INFORMACIÓN PROPORCIONADA POR EL CLIENTE -->
        <div>

            <!-- TARJETA 1: DATOS DEL CONSUMIDOR -->
            <div class="rec-card">
                <h2>1. Identificación del Consumidor Reclamante</h2>
                <dl class="rec-dl">
                    <div>
                        <dt>Tipo de Persona</dt>
                        <dd>{{ ucfirst($reclamacion->tipo_persona) }}</dd>
                    </div>
                    <div>
                        <dt>Nombres y Apellidos / Razón Social</dt>
                        <dd>{{ $reclamacion->nombre }}</dd>
                    </div>
                    <div>
                        <dt>{{ $reclamacion->tipo_documento }}</dt>
                        <dd>{{ $reclamacion->documento }}</dd>
                    </div>
                    <div>
                        <dt>Correo Electrónico</dt>
                        <dd><a href="mailto:{{ $reclamacion->email }}" style="color: #0284c7; text-decoration: none;">{{ $reclamacion->email }}</a></dd>
                    </div>
                    <div>
                        <dt>Teléfono de Contacto</dt>
                        <dd>{{ $reclamacion->telefono ?: 'No especificado' }}</dd>
                    </div>
                    <div>
                        <dt>País de Residencia</dt>
                        <dd>{{ $reclamacion->pais }}</dd>
                    </div>
                </dl>
            </div>

            <!-- TARJETA 2: DATOS DEL BIEN CONTRATADO -->
            <div class="rec-card">
                <h2>2. Identificación del Bien Contratado</h2>
                <dl class="rec-dl">
                    <div style="grid-column: span 2;">
                        <dt>Producto o Servicio Contratado</dt>
                        <dd style="font-size: 15px; color: #0f172a;">{{ $reclamacion->producto }}</dd>
                    </div>
                    <div>
                        <dt>N° de Reserva Asociada</dt>
                        <dd>
                            @if($reclamacion->reserva)
                                <a href="{{ url('/admin/reservas/' . $reclamacion->reserva) }}" style="color: #0284c7; font-weight: 700; text-decoration: none;">
                                    Reserva #{{ $reclamacion->reserva }} &rarr;
                                </a>
                            @else
                                <span style="color: #94a3b8;">No aplica</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>Monto Reclamado</dt>
                        <dd>
                            @if($reclamacion->monto)
                                <strong style="font-size: 15px; color: #0f172a;">{{ $reclamacion->moneda }} {{ number_format($reclamacion->monto, 2) }}</strong>
                            @else
                                <span style="color: #94a3b8;">Sin monto pecuniario</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt>Formato de Respuesta Solicitado</dt>
                        <dd>{{ ucfirst($reclamacion->formato_respuesta) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- TARJETA 3: DETALLE DEL RECLAMO Y PEDIDO -->
            <div class="rec-card">
                <h2>3. Detalle de los Hechos y Solicitud</h2>

                <div style="margin-bottom: 16px;">
                    <span style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Motivo del Reclamo</span>
                    <div style="font-weight: 700; font-size: 14.5px; color: #0f172a; margin-top: 2px;">
                        {{ $reclamacion->motivo_label }}
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <span style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Detalle y Descripción de los Hechos</span>
                    <div class="rec-box-text">
                        {{ $reclamacion->detalle }}
                    </div>
                </div>

                <div>
                    <span style="font-size: 11.5px; font-weight: 700; text-transform: uppercase; color: #64748b;">Pedido Concreto del Consumidor</span>
                    <div class="rec-box-text" style="background: #fffbeb; border-color: #fde68a;">
                        {{ $reclamacion->pedido }}
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA: GESTIÓN ADMINISTRATIVA Y RESPUESTA FORMAL -->
        <div>

            <div class="rec-card" style="border-top: 4px solid #0f172a;">
                <h2>4. Gestión y Respuesta del Proveedor</h2>

                <form method="POST" action="{{ route('admin.reclamaciones.update', $reclamacion->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="rec-form-group">
                        <label for="estado" class="rec-form-label">Estado de la Reclamación</label>
                        <select id="estado" name="estado" class="rec-form-select" required>
                            <option value="pendiente" {{ $reclamacion->estado === 'pendiente' ? 'selected' : '' }}>Pendiente (Sin responder)</option>
                            <option value="en_proceso" {{ $reclamacion->estado === 'en_proceso' ? 'selected' : '' }}>En proceso (En investigación u operaciones)</option>
                            <option value="atendido" {{ $reclamacion->estado === 'atendido' ? 'selected' : '' }}>Atendido (Respuesta formal emitida)</option>
                            <option value="archivado" {{ $reclamacion->estado === 'archivado' ? 'selected' : '' }}>Archivado (Cerrado por improcedencia)</option>
                        </select>
                    </div>

                    <div class="rec-form-group">
                        <label for="respuesta" class="rec-form-label">Respuesta Oficial al Consumidor</label>
                        <textarea
                            id="respuesta"
                            name="respuesta"
                            rows="7"
                            class="rec-form-textarea"
                            placeholder="Redacta la respuesta formal que será notificada al correo del consumidor..."
                        >{{ old('respuesta', $reclamacion->respuesta) }}</textarea>
                        <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">
                            Esta respuesta queda registrada en el expediente legal de la empresa.
                        </small>
                    </div>

                    @if($reclamacion->fecha_respuesta)
                        <div style="background: #f1f5f9; padding: 10px 14px; border-radius: 8px; font-size: 12.5px; margin-bottom: 16px; color: #334155;">
                            Última respuesta registrada: <strong>{{ $reclamacion->fecha_respuesta->format('d/m/Y H:i') }}</strong>
                            @if($reclamacion->respondidoPor)
                                por <strong>{{ $reclamacion->respondidoPor->name }}</strong>
                            @endif
                        </div>
                    @endif

                    <button type="submit" class="rec-btn-save">
                        Guardar Cambios y Notificar
                    </button>
                </form>

                <div class="rec-legal-notice">
                    <strong>Marco Normativo:</strong> Conforme al D.S. N° 011-2011-PCM e INDECOPI, el proveedor cuenta con un plazo máximo improrrogable de <strong>quince (15) días hábiles</strong> para emitir respuesta a esta reclamación o queja.
                </div>
            </div>

            <!-- TARJETA EXTRA: ACCIONES RÁPIDAS -->
            <div class="rec-card">
                <h2>Acciones Rápidas</h2>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="mailto:{{ $reclamacion->email }}?subject=World%20Travel%20-%20Respuesta%20a%20Reclamaci%C3%B3n%20{{ $reclamacion->codigo }}" class="rec-btn rec-btn--secondary" style="justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-10 6L2 7"></path>
                        </svg>
                        Contactar al cliente por Email
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
