@extends('admin')

@section('title', 'Gestión de Alojamientos')
@section('active', 'alojamientos')

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

    /* STATS CARDS */
    .alojamiento-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    .alojamiento-stat {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 4px 15px rgba(20, 50, 75, .03);
    }
    .alojamiento-stat__label {
        font-size: 12px;
        font-weight: 700;
        color: #64798a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .alojamiento-stat__value {
        font-size: 26px;
        font-weight: 800;
        color: #102331;
        margin-top: 4px;
    }

    /* FILTROS */
    .alojamiento-filter-card {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(20, 50, 75, .03);
    }
    .alojamiento-filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .alojamiento-filter-group {
        flex: 1;
        min-width: 180px;
    }
    .alojamiento-filter-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #475569;
    }
    .alojamiento-input, .alojamiento-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
    }
    .alojamiento-btn-filter {
        background: #106d9f;
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
    }
    .alojamiento-btn-filter:hover {
        background: #084e74;
    }
    .alojamiento-btn-clean {
        background: transparent;
        color: #64798a;
        border: 1px solid #cfdce5;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    /* TABLA */
    .wt-admin__card {
        background: #ffffff;
        border: 1px solid #e0e9ef;
        border-radius: 15px;
        overflow-x: auto;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
    }
    .wt-admin__table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .wt-admin__table th {
        background: rgba(128, 155, 172, .09);
        padding: 15px 18px;
        text-align: left;
        font-size: 11px;
        color: #536e80;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    .wt-admin__table td {
        padding: 15px 18px;
        border-top: 1px solid #e8eef2;
        vertical-align: middle;
        font-size: 13px;
    }
    .alojamiento-cell {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .alojamiento-thumb {
        width: 70px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        background: #e2e8f0;
        flex-shrink: 0;
    }
    .alojamiento-title {
        font-weight: 700;
        font-size: 14px;
        color: #102331;
    }
    .alojamiento-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 3px;
    }
    .alojamiento-badge-tipo {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        background: #e0f2fe;
        color: #0369a1;
    }
    .alojamiento-price {
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
    }
    .alojamiento-subprice {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
        margin-top: 2px;
    }
    .wt-admin__status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .wt-admin__status--on {
        background: #e5f7ed;
        color: #177344;
    }
    .wt-admin__status--off {
        background: #f1f5f9;
        color: #64748b;
    }
    .wt-admin__actions {
        display: flex;
        align-items: center;
        gap: 10px;
        white-space: nowrap;
    }
    .wt-admin__edit {
        color: #106d9f;
        font-weight: 700;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #bae6fd;
        background: #f0f9ff;
    }
    .wt-admin__edit:hover {
        background: #e0f2fe;
    }
    .wt-admin__delete {
        background: #fff1f0;
        color: #b42318;
        border: 1px solid #f3c5c0;
        border-radius: 6px;
        padding: 6px 10px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        font-family: inherit;
    }
    .wt-admin__delete:hover {
        background: #ffe0dc;
    }
    .wt-admin__alert {
        background: #e5f7ed;
        color: #17683f;
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #b9e3c7;
        font-weight: 600;
    }
    .wt-admin__empty {
        padding: 50px 20px;
        text-align: center;
        color: #64798a;
    }
</style>
@endpush

@section('content')
<div class="wt-admin">

    @if(session('success'))
        <div class="wt-admin__alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="wt-admin__top">
        <div>
            <span class="wt-admin__label">Base de Datos MySQL</span>
            <h1>Gestión de Alojamientos</h1>
            <p class="wt-admin__desc">
                Administra los hoteles, resorts y hospedajes disponibles en la plataforma.
            </p>
        </div>

        <a href="{{ route('admin.alojamientos.create') }}" class="wt-admin__button">
            + Nuevo Alojamiento
        </a>
    </div>

    <!-- TARJETAS DE RESUMEN -->
    <div class="alojamiento-stats">
        <div class="alojamiento-stat">
            <div class="alojamiento-stat__label">Total Alojamientos</div>
            <div class="alojamiento-stat__value">{{ $totalAlojamientos }}</div>
        </div>
        <div class="alojamiento-stat">
            <div class="alojamiento-stat__label">Hoteles</div>
            <div class="alojamiento-stat__value">{{ $hotelesCount }}</div>
        </div>
        <div class="alojamiento-stat">
            <div class="alojamiento-stat__label">Resorts</div>
            <div class="alojamiento-stat__value">{{ $resortsCount }}</div>
        </div>
        <div class="alojamiento-stat">
            <div class="alojamiento-stat__label">Hostales</div>
            <div class="alojamiento-stat__value">{{ $hostalesCount }}</div>
        </div>
    </div>

    <!-- BARRA DE BÚSQUEDA Y FILTROS -->
    <div class="alojamiento-filter-card">
        <form method="GET" action="{{ route('admin.alojamientos.index') }}" class="alojamiento-filter-form">
            <div class="alojamiento-filter-group" style="flex: 2;">
                <label class="alojamiento-filter-label" for="q">Buscar alojamiento o destino</label>
                <input type="text" id="q" name="q" value="{{ $busqueda }}" placeholder="Nombre, código o ciudad..." class="alojamiento-input">
            </div>

            <div class="alojamiento-filter-group">
                <label class="alojamiento-filter-label" for="tipo">Tipo</label>
                <select id="tipo" name="tipo" class="alojamiento-select">
                    <option value="">Todos los tipos</option>
                    <option value="hotel" {{ $tipo === 'hotel' ? 'selected' : '' }}>Hotel</option>
                    <option value="resort" {{ $tipo === 'resort' ? 'selected' : '' }}>Resort</option>
                    <option value="hostal" {{ $tipo === 'hostal' ? 'selected' : '' }}>Hostal</option>
                    <option value="boutique" {{ $tipo === 'boutique' ? 'selected' : '' }}>Boutique</option>
                </select>
            </div>

            <div class="alojamiento-filter-group">
                <label class="alojamiento-filter-label" for="estado">Estado</label>
                <select id="estado" name="estado" class="alojamiento-select">
                    <option value="">Todos</option>
                    <option value="activo" {{ $estado === 'activo' ? 'selected' : '' }}>Activos</option>
                    <option value="inactivo" {{ $estado === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div>
                <button type="submit" class="alojamiento-btn-filter">Buscar</button>
                @if($busqueda || $tipo || $estado)
                    <a href="{{ route('admin.alojamientos.index') }}" class="alojamiento-btn-clean">Limpiar</a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABLA DE ALOJAMIENTOS -->
    <div class="wt-admin__card">
        <table class="wt-admin__table">
            <thead>
                <tr>
                    <th>Alojamiento</th>
                    <th>Destino</th>
                    <th>Tipo</th>
                    <th>Tarifa / Noche</th>
                    <th>Calificación</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alojamientos as $item)
                    <tr>
                        <td>
                            <div class="alojamiento-cell">
                                <img src="{{ $item->imagen_url }}" alt="{{ $item->nombre }}" class="alojamiento-thumb" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('icons/hotel1.jpg') }}';">
                                <div>
                                    <div class="alojamiento-title">{{ $item->nombre }}</div>
                                    <div class="alojamiento-sub">
                                        Código: <code>{{ $item->codigo }}</code>
                                        @if($item->habitacion)
                                            · {{ $item->habitacion }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <strong>{{ $item->ubicacion ?? 'Ubicación' }}</strong>
                        </td>

                        <td>
                            <span class="alojamiento-badge-tipo">
                                {{ $item->tipo }}
                            </span>
                        </td>

                        <td>
                            @if($item->moneda_simbolo === 'S/')
                                <div class="alojamiento-price">S/ {{ number_format($item->precio_noche, 2) }}</div>
                                <div class="alojamiento-subprice">aprox. US$ {{ number_format($item->precio_noche / 3.51, 2) }}</div>
                            @else
                                <div class="alojamiento-price">US$ {{ number_format($item->precio_noche, 2) }}</div>
                                <div class="alojamiento-subprice">aprox. S/ {{ number_format($item->precio_noche * 3.51, 2) }}</div>
                            @endif
                        </td>

                        <td>
                            <strong>{{ $item->puntuacion }}/5</strong>
                            <div class="alojamiento-sub">{{ $item->estrellas }} estrellas</div>
                        </td>

                        <td>
                            @if($item->activo)
                                <span class="wt-admin__status wt-admin__status--on">Activo</span>
                            @else
                                <span class="wt-admin__status wt-admin__status--off">Inactivo</span>
                            @endif
                        </td>

                        <td style="text-align: right;">
                            <div class="wt-admin__actions" style="justify-content: flex-end;">
                                <a href="{{ route('alojamientos.detalle', ['codigo' => $item->codigo]) }}" target="_blank" class="wt-admin__edit" style="color: #475569; border-color: #cbd5e1; background: #f8fafc;" title="Ver en portal público">
                                    Ver
                                </a>
                                <a href="{{ route('admin.alojamientos.edit', $item->id) }}" class="wt-admin__edit">
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('admin.alojamientos.destroy', $item->id) }}" onsubmit="return confirm('¿Confirmas eliminar este alojamiento? Esta acción no se puede deshacer.');" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="wt-admin__delete">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="wt-admin__empty">
                            No se encontraron alojamientos registrados en la base de datos con los criterios indicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($alojamientos->hasPages())
        <div style="margin-top: 25px;">
            {{ $alojamientos->links() }}
        </div>
    @endif

</div>
@endsection
