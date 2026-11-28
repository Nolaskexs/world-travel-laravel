@extends('admin')

@section('title', 'Gestión de Vuelos Comerciales')
@section('active', 'vuelos')

@push('styles')
<style>
    .wt-admin {
        max-width: 1200px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }
    .wt-admin__top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }
    .wt-admin__label {
        color: #1377ae;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .wt-admin h1 {
        font-size: clamp(24px, 4vw, 32px);
        margin: 6px 0;
        font-weight: 700;
    }
    .wt-admin__desc {
        color: var(--slate, #64798a);
        margin: 0;
        font-size: 14px;
    }
    .wt-admin__button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #106d9f;
        color: white !important;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        transition: background .2s ease;
    }
    .wt-admin__button:hover {
        background: #084e74;
    }
    .vuelo-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    .vuelo-stat {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 4px 15px rgba(20, 50, 75, .03);
    }
    .vuelo-stat__label {
        font-size: 12px;
        font-weight: 700;
        color: #64798a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .vuelo-stat__value {
        font-size: 26px;
        font-weight: 800;
        color: #102331;
        margin-top: 5px;
    }
    .wt-filter-bar {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }
    .wt-search-input {
        flex: 1;
        min-width: 200px;
        padding: 10px 14px;
        border: 1px solid #cbdbe6;
        border-radius: 8px;
        font-size: 14px;
    }
    .wt-select-filter {
        padding: 10px 14px;
        border: 1px solid #cbdbe6;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
    }
    .wt-table-wrap {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 14px;
        overflow-x: auto;
        box-shadow: 0 6px 20px rgba(20, 50, 75, .04);
    }
    .wt-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }
    .wt-table th {
        background: #f7fafc;
        padding: 14px 18px;
        font-weight: 700;
        color: #486581;
        border-bottom: 1px solid #e0e9ef;
        white-space: nowrap;
    }
    .wt-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #f0f4f8;
        vertical-align: middle;
    }
    .wt-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
    }
    .wt-badge--flight {
        background: #e0f2fe;
        color: #0369a1;
        font-family: monospace;
        font-size: 13px;
    }
    .wt-badge--direct {
        background: #dcfce7;
        color: #15803d;
    }
    .wt-badge--scale {
        background: #fef3c7;
        color: #b45309;
    }
    .wt-actions {
        display: flex;
        gap: 8px;
    }
    .wt-btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .wt-btn-action--edit {
        background: #f0f7fb;
        color: #106d9f;
        border-color: #cde4f3;
    }
    .wt-btn-action--edit:hover {
        background: #e1eff7;
    }
    .wt-btn-action--delete {
        background: #fff5f5;
        color: #c53030;
        border-color: #fed7d7;
    }
    .wt-btn-action--delete:hover {
        background: #fed7d7;
    }
</style>
@endpush

@section('content')
<div class="wt-admin">
    <div class="wt-admin__top">
        <div>
            <div class="wt-admin__label">Itinerario Aéreo Comercial</div>
            <h1>Gestión de Vuelos</h1>
            <p class="wt-admin__desc">Programa itinerarios aéreos, horarios, aerolíneas, escalas y tarifas por pasajero.</p>
        </div>
        <a href="{{ route('admin.vuelos.create') }}" class="wt-admin__button">
            + Nuevo Vuelo
        </a>
    </div>

    @if(session('success'))
        <div style="background:#e6fffa;border:1px solid #b2f5ea;color:#234e52;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fff5f5;border:1px solid #feb2b2;color:#742a2a;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-weight:600;">
            {{ session('error') }}
        </div>
    @endif

    <div class="vuelo-stats">
        <div class="vuelo-stat">
            <div class="vuelo-stat__label">Total Vuelos</div>
            <div class="vuelo-stat__value">{{ $totalVuelos }}</div>
        </div>
        <div class="vuelo-stat">
            <div class="vuelo-stat__label">Aerolíneas Asociadas</div>
            <div class="vuelo-stat__value" style="color:#1377ae;">{{ $aerolineasDistintas }}</div>
        </div>
        <div class="vuelo-stat">
            <div class="vuelo-stat__label">Asientos Ofertados</div>
            <div class="vuelo-stat__value" style="color:#15803d;">{{ number_format($asientosTotales) }}</div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.vuelos.index') }}" class="wt-filter-bar">
        <input type="text" name="q" value="{{ $busqueda }}" placeholder="Buscar por código, aerolínea o ciudad..." class="wt-search-input">
        
        <select name="aerolinea" class="wt-select-filter" onchange="this.form.submit()">
            <option value="">Todas las aerolíneas</option>
            @foreach($aerolineasList as $aero)
                <option value="{{ $aero }}" {{ $aerolinea === $aero ? 'selected' : '' }}>{{ $aero }}</option>
            @endforeach
        </select>

        <select name="estado" class="wt-select-filter" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="activo" {{ $estado === 'activo' ? 'selected' : '' }}>Activos</option>
            <option value="inactivo" {{ $estado === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
        </select>

        <button type="submit" class="wt-admin__button" style="padding:10px 16px;">Filtrar</button>
        @if($busqueda !== '' || $aerolinea !== '' || $estado !== '')
            <a href="{{ route('admin.vuelos.index') }}" style="color:#64748b;font-size:13px;text-decoration:none;font-weight:600;">Limpiar</a>
        @endif
    </form>

    <div class="wt-table-wrap">
        <table class="wt-table">
            <thead>
                <tr>
                    <th>Vuelo</th>
                    <th>Ruta (Origen → Destino)</th>
                    <th>Horario (Salida → Llegada)</th>
                    <th>Escalas / Duración</th>
                    <th>Tarifa Final</th>
                    <th>Asientos</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vuelos as $vuelo)
                    <tr>
                        <td>
                            <span class="wt-badge wt-badge--flight">{{ $vuelo->codigo_vuelo }}</span>
                            <small style="display:block;color:#64798a;font-weight:600;margin-top:4px;">{{ $vuelo->aerolinea }}</small>
                        </td>
                        <td>
                            <strong style="color:#102331;">{{ $vuelo->origen?->nombre }} ({{ $vuelo->origen?->codigo_iata }})</strong>
                            <span style="color:#64748b;margin:0 4px;">→</span>
                            <strong style="color:#106d9f;">{{ $vuelo->destino?->nombre }} ({{ $vuelo->destino?->codigo_iata }})</strong>
                        </td>
                        <td>
                            <span style="font-size:13px;color:#334e68;display:block;">
                                {{ $vuelo->fecha_salida?->format('d/m/Y H:i') }}
                            </span>
                            <small style="color:#64798a;">
                                Llega: {{ $vuelo->fecha_llegada?->format('d/m/Y H:i') }}
                            </small>
                        </td>
                        <td>
                            @if($vuelo->cantidad_escalas == 0)
                                <span class="wt-badge wt-badge--direct">Directo</span>
                            @else
                                <span class="wt-badge wt-badge--scale">{{ $vuelo->cantidad_escalas }} escala(s)</span>
                            @endif
                            <small style="display:block;color:#64798a;margin-top:3px;">{{ $vuelo->duracion_formateada }}</small>
                        </td>
                        <td>
                            <strong style="font-size:15px;color:#102331;">US$ {{ number_format($vuelo->precio_final, 2) }}</strong>
                            <small style="display:block;color:#64798a;">Base: US$ {{ number_format($vuelo->precio_adulto, 2) }}</small>
                        </td>
                        <td>
                            <span style="font-weight:700;color:#15803d;">{{ $vuelo->asientos_disponibles }} disp.</span>
                        </td>
                        <td style="text-align:right;">
                            <div class="wt-actions" style="justify-content:flex-end;">
                                <a href="{{ route('admin.vuelos.edit', $vuelo) }}" class="wt-btn-action wt-btn-action--edit">Editar</a>
                                <form method="POST" action="{{ route('admin.vuelos.destroy', $vuelo) }}" onsubmit="return confirm('¿Seguro que deseas eliminar el vuelo {{ $vuelo->codigo_vuelo }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="wt-btn-action wt-btn-action--delete">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:40px 20px;color:#64798a;">
                            No se encontraron vuelos programados con los filtros seleccionados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $vuelos->links() }}
    </div>
</div>
@endsection
