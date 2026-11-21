@extends('admin')

@section('title', 'Libro de Reclamaciones')
@section('active', 'reclamaciones')

@push('styles')
<style>
    .rec-page {
        max-width: 1280px;
        margin: 0 auto;
        padding: 30px 25px 60px;
        color: var(--ink, #16354a);
    }

    .rec-header {
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .rec-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rec-header p {
        color: var(--slate, #64748b);
        font-size: 14px;
        margin: 0;
    }

    /* Tarjetas de estadísticas */
    .rec-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 25px;
    }

    .rec-stat {
        background: var(--surface, #ffffff);
        border: 1px solid #dae5ec;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .rec-stat span {
        display: block;
        font-size: 13px;
        color: var(--slate, #64748b);
        margin-bottom: 6px;
        font-weight: 500;
    }

    .rec-stat strong {
        font-size: 26px;
        font-weight: 800;
        line-height: 1;
    }

    .rec-stat--pending strong { color: #d97706; }
    .rec-stat--progress strong { color: #0284c7; }
    .rec-stat--done strong { color: #16a34a; }

    /* Panel principal */
    .rec-panel {
        background: var(--surface, #ffffff);
        border: 1px solid #dae5ec;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    .rec-toolbar {
        padding: 20px;
        border-bottom: 1px solid #edf2f7;
        background: #fafbfc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .rec-search-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        flex: 1;
    }

    .rec-search-input {
        min-width: 260px;
        padding: 8px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13.5px;
        background: #ffffff;
        color: #1e293b;
        outline: none;
    }

    .rec-search-input:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
    }

    .rec-select {
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 13.5px;
        background: #ffffff;
        color: #1e293b;
        outline: none;
        cursor: pointer;
    }

    .rec-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.15s ease;
        border: none;
    }

    .rec-btn--primary {
        background: #0f172a;
        color: #ffffff;
    }

    .rec-btn--primary:hover {
        background: #1e293b;
    }

    .rec-btn--secondary {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .rec-btn--secondary:hover {
        background: #e2e8f0;
    }

    /* Tabla */
    .rec-table-wrap {
        overflow-x: auto;
    }

    .rec-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
        text-align: left;
    }

    .rec-table th {
        background: #f8fafc;
        padding: 12px 18px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    .rec-table td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
    }

    .rec-table tbody tr:hover {
        background: #f8fafc;
    }

    /* Badges */
    .rec-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .rec-badge--reclamo {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .rec-badge--queja {
        background: #faf5ff;
        color: #7e22ce;
        border: 1px solid #e9d5ff;
    }

    .rec-badge--pendiente {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .rec-badge--en_proceso {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .rec-badge--atendido {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .rec-badge--archivado {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .rec-code {
        font-family: monospace;
        font-weight: 800;
        color: #0f172a;
        font-size: 13px;
    }

    .rec-consumer-name {
        font-weight: 700;
        color: #0f172a;
        display: block;
    }

    .rec-consumer-meta {
        font-size: 12px;
        color: #64748b;
    }

    .rec-empty {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .rec-empty h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
    }

    @media (max-width: 768px) {
        .rec-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="rec-page">

    <!-- ENCABEZADO -->
    <div class="rec-header">
        <div>
            <h1>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                Libro de Reclamaciones
            </h1>
            <p>Monitoreo legal, atención y respuesta formal a los reclamos y quejas de los usuarios conforme a INDECOPI.</p>
        </div>
        <div>
            <a href="{{ route('reclamaciones') }}" target="_blank" class="rec-btn rec-btn--secondary">
                Ver Formulario Público &rarr;
            </a>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS -->
    <div class="rec-stats">
        <div class="rec-stat">
            <span>Total de Registros</span>
            <strong>{{ $stats['total'] }}</strong>
        </div>
        <div class="rec-stat rec-stat--pending">
            <span>Pendientes de Atención</span>
            <strong>{{ $stats['pendientes'] }}</strong>
        </div>
        <div class="rec-stat rec-stat--progress">
            <span>En Proceso / Derivados</span>
            <strong>{{ $stats['en_proceso'] }}</strong>
        </div>
        <div class="rec-stat rec-stat--done">
            <span>Atendidos / Resueltos</span>
            <strong>{{ $stats['atendidos'] }}</strong>
        </div>
    </div>

    <!-- PANEL PRINCIPAL CON FILTROS Y TABLA -->
    <div class="rec-panel">
        <div class="rec-toolbar">
            <form method="GET" action="{{ route('admin.reclamaciones.index') }}" class="rec-search-form">
                <input
                    type="text"
                    name="q"
                    class="rec-search-input"
                    placeholder="Buscar por código, cliente, DNI, producto o reserva..."
                    value="{{ $q }}"
                >

                <select name="estado" class="rec-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_proceso" {{ $estado === 'en_proceso' ? 'selected' : '' }}>En proceso</option>
                    <option value="atendido" {{ $estado === 'atendido' ? 'selected' : '' }}>Atendido</option>
                    <option value="archivado" {{ $estado === 'archivado' ? 'selected' : '' }}>Archivado</option>
                </select>

                <select name="tipo" class="rec-select">
                    <option value="">Reclamos y Quejas</option>
                    <option value="reclamo" {{ $tipo === 'reclamo' ? 'selected' : '' }}>Solo Reclamos</option>
                    <option value="queja" {{ $tipo === 'queja' ? 'selected' : '' }}>Solo Quejas</option>
                </select>

                <button type="submit" class="rec-btn rec-btn--primary">Filtrar</button>

                @if($q || $estado || $tipo)
                    <a href="{{ route('admin.reclamaciones.index') }}" class="rec-btn rec-btn--secondary">Limpiar</a>
                @endif
            </form>
        </div>

        <div class="rec-table-wrap">
            <table class="rec-table">
                <thead>
                    <tr>
                        <th>Código / Fecha</th>
                        <th>Consumidor / Reclamante</th>
                        <th>Tipo</th>
                        <th>Producto / Servicio</th>
                        <th>Monto Reclamado</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reclamaciones as $rec)
                        <tr>
                            <td>
                                <span class="rec-code">{{ $rec->codigo }}</span>
                                <div class="rec-consumer-meta">{{ $rec->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <span class="rec-consumer-name">{{ $rec->nombre }}</span>
                                <span class="rec-consumer-meta">
                                    {{ $rec->tipo_documento }}: {{ $rec->documento }} &bull; {{ $rec->email }}
                                </span>
                            </td>
                            <td>
                                <span class="rec-badge rec-badge--{{ $rec->tipo }}">
                                    {{ ucfirst($rec->tipo) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #1e293b;">{{ $rec->producto }}</div>
                                @if($rec->reserva)
                                    <div class="rec-consumer-meta">Reserva N°: <strong>{{ $rec->reserva }}</strong></div>
                                @endif
                            </td>
                            <td>
                                @if($rec->monto)
                                    <strong>{{ $rec->moneda }} {{ number_format($rec->monto, 2) }}</strong>
                                @else
                                    <span class="rec-consumer-meta">Sin monto</span>
                                @endif
                            </td>
                            <td>
                                <span class="rec-badge rec-badge--{{ $rec->estado }}">
                                    {{ $rec->estado_label }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="{{ route('admin.reclamaciones.show', $rec->id) }}" class="rec-btn rec-btn--secondary" style="padding: 6px 12px; font-size: 12.5px;">
                                    Revisar &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="rec-empty">
                                    <h3>No se encontraron hojas de reclamación</h3>
                                    <p>Intenta ajustar los filtros de búsqueda o el estado seleccionado.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reclamaciones->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #edf2f7;">
                {{ $reclamaciones->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
