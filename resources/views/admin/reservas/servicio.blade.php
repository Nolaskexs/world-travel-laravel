@extends('admin')

@section('title', 'Reservas de '.$servicioInfo['plural'])
@section('active', 'reservas')

@push('styles')
<style>
    .reservas-page {
        max-width: 1250px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }

    .reservas-header {
        margin-bottom: 22px;
    }

    .reservas-back {
        display: inline-block;
        color: #1377ae;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 10px;
    }

    .reservas-back:hover {
        text-decoration: underline;
    }

    .reservas-header h1 {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .reservas-header p {
        color: var(--slate, #64748b);
        margin: 0;
        line-height: 1.6;
    }

    .reservas-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 22px;
        border-bottom: 1px solid #dae5ec;
        padding-bottom: 10px;
    }

    .reservas-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 15px;
        border: 1px solid #d5e2ea;
        border-radius: 9px;
        color: #475569;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        background: #fff;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .reservas-tab:hover {
        background: #eef7fc;
        border-color: #9dc7df;
    }

    .reservas-tab--active {
        background: #106d9f;
        border-color: #106d9f;
        color: #fff;
    }

    .reservas-tab__count {
        background: rgba(16, 109, 159, .12);
        color: #106d9f;
        border-radius: 20px;
        padding: 2px 8px;
        font-size: 12px;
    }

    .reservas-tab--active .reservas-tab__count {
        background: rgba(255, 255, 255, .22);
        color: #fff;
    }

    .reservas-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 25px;
    }

    .reservas-stat {
        background: var(--surface, white);
        border: 1px solid #dae5ec;
        border-radius: 13px;
        padding: 22px;
        box-shadow: 0 4px 15px rgba(20, 50, 75, .03);
    }

    .reservas-stat span {
        display: block;
        font-size: 13px;
        color: var(--slate, #64748b);
        margin-bottom: 10px;
    }

    .reservas-stat strong {
        font-size: 28px;
        font-weight: 800;
    }

    .reservas-panel {
        background: var(--surface, white);
        border: 1px solid #dae5ec;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
    }

    .reservas-toolbar {
        padding: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .reservas-toolbar h2 {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }

    .reservas-search {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .reservas-search input,
    .reservas-search select {
        padding: 11px 13px;
        border: 1px solid #cbd8e0;
        border-radius: 8px;
        background: var(--surface, white);
        color: var(--ink, #16354a);
        font: inherit;
    }

    .reservas-search input {
        min-width: 220px;
    }

    .reservas-btn {
        background: #106d9f;
        color: #fff;
        padding: 11px 17px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease;
    }

    .reservas-btn:hover {
        background: #095478;
    }

    .reservas-clear {
        align-self: center;
        color: #1377ae;
        text-decoration: none;
        font-weight: 600;
    }

    .reservas-scroll {
        overflow-x: auto;
    }

    .reservas-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
    }

    .reservas-table th {
        background: rgba(128, 155, 172, .09);
        padding: 15px;
        color: var(--slate, #64748b);
        text-align: left;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .reservas-table td {
        padding: 17px 15px;
        border-top: 1px solid #e6edf2;
        font-size: 13px;
    }

    .reservas-name {
        font-weight: 700;
    }

    .reservas-sub {
        color: var(--slate, #64748b);
        font-size: 12px;
        margin-top: 5px;
    }

    .reservas-price {
        font-weight: 800;
        white-space: nowrap;
    }

    .reservas-service-badge,
    .reservas-badge {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .reservas-service-badge {
        background: #e0f2fe;
        color: #0369a1;
    }

    .reservas-badge--pendiente {
        background: #fff2d8;
        color: #976300;
    }

    .reservas-badge--confirmada {
        background: #e4f7ed;
        color: #177344;
    }

    .reservas-badge--cancelada {
        background: #fde9e8;
        color: #b42318;
    }

    .reservas-link {
        color: #1377ae;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .reservas-link:hover {
        text-decoration: underline;
    }

    .reservas-empty {
        text-align: center;
        padding: 45px 20px;
        color: var(--slate, #64748b);
    }

    .reservas-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 18px;
        padding: 22px;
    }

    .reservas-pagination a {
        color: #1377ae;
        font-weight: 700;
        text-decoration: none;
    }

    @media (max-width: 850px) {
        .reservas-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 520px) {
        .reservas-page {
            padding: 25px 15px 50px;
        }

        .reservas-stats {
            grid-template-columns: 1fr;
        }

        .reservas-search input {
            min-width: 100%;
        }
    }
</style>
@endpush

@section('content')

<main class="reservas-page">

    <header class="reservas-header">
        <a class="reservas-back" href="{{ route('admin.reservas.index') }}">
            ← Volver al resumen de reservas
        </a>

        <h1>Reservas de {{ $servicioInfo['plural'] }}</h1>

        <p>{{ $servicioInfo['descripcion'] }}</p>
    </header>

    <nav class="reservas-tabs" aria-label="Subsecciones de reservas">
        @foreach ($servicios as $clave => $item)
            <a
                class="reservas-tab {{ $clave === $servicio ? 'reservas-tab--active' : '' }}"
                href="{{ $item['ruta'] }}"
                @if ($clave === $servicio) aria-current="page" @endif
            >
                {{ $item['plural'] }}
                <span class="reservas-tab__count">{{ $item['total'] }}</span>
            </a>
        @endforeach
    </nav>

    <section class="reservas-stats" aria-label="Resumen de reservas de {{ $servicioInfo['plural'] }}">
        <article class="reservas-stat">
            <span>Total de {{ $servicioInfo['plural'] }}</span>
            <strong>{{ $totalReservas }}</strong>
        </article>

        <article class="reservas-stat">
            <span>Pendientes</span>
            <strong>{{ $pendientes }}</strong>
        </article>

        <article class="reservas-stat">
            <span>Confirmadas</span>
            <strong>{{ $confirmadas }}</strong>
        </article>

        <article class="reservas-stat">
            <span>Canceladas</span>
            <strong>{{ $canceladas }}</strong>
        </article>
    </section>

    <section class="reservas-panel">
        <div class="reservas-toolbar">
            <h2>Lista de reservas de {{ $servicioInfo['plural'] }}</h2>

            <form
                class="reservas-search"
                method="GET"
                action="{{ $servicioInfo['ruta'] }}"
            >
                <input
                    type="search"
                    name="q"
                    value="{{ $busqueda }}"
                    placeholder="Cliente, correo, servicio o ID"
                    aria-label="Buscar reservas de {{ $servicioInfo['plural'] }}"
                >

                <select name="estado" aria-label="Filtrar por estado">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" @selected($estado === 'pendiente')>
                        Pendientes
                    </option>
                    <option value="confirmada" @selected($estado === 'confirmada')>
                        Confirmadas
                    </option>
                    <option value="cancelada" @selected($estado === 'cancelada')>
                        Canceladas
                    </option>
                </select>

                <button class="reservas-btn" type="submit">
                    Buscar
                </button>

                <a
                    class="reservas-clear"
                    href="{{ $servicioInfo['ruta'] }}"
                >
                    Limpiar
                </a>
            </form>
        </div>

        <div class="reservas-scroll">
            <table class="reservas-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Detalle</th>
                        <th>Fecha de viaje</th>
                        <th>Pasajeros</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id }}</td>

                            <td>
                                <div class="reservas-name">
                                    {{ $reserva->cliente_nombre }}
                                </div>
                                <div class="reservas-sub">
                                    {{ $reserva->cliente_email }}
                                </div>
                            </td>

                            <td>
                                <span class="reservas-service-badge">
                                    {{ $servicioInfo['singular'] }}
                                </span>
                            </td>

                            <td>
                                {{ $reserva->paquete_titulo }}
                                @if (! empty($reserva->asientos_texto))
                                    <div class="reservas-sub" style="color: #0284c7;">
                                        Asientos: {{ $reserva->asientos_texto }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ \Illuminate\Support\Carbon::parse($reserva->fecha_viaje)->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $reserva->num_pasajeros }}
                                {{ $reserva->num_pasajeros == 1 ? 'persona' : 'personas' }}
                            </td>

                            <td class="reservas-price">
                                <div>US$ {{ number_format((float) $reserva->total, 2) }}</div>
                                <small class="reservas-sub">
                                    aprox. S/ {{ number_format((float) $reserva->total * 3.51, 2) }}
                                </small>
                            </td>

                            <td>
                                <span class="reservas-badge reservas-badge--{{ $reserva->estado }}">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>

                            <td>
                                <a
                                    class="reservas-link"
                                    href="{{ route('admin.reservas.show', $reserva->id) }}"
                                >
                                    Ver detalles
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="reservas-empty">
                                    @if ($busqueda !== '' || $estado !== '')
                                        No se encontraron reservas de {{ $servicioInfo['plural'] }} con los filtros seleccionados.
                                    @else
                                        Todavía no existen reservas de {{ $servicioInfo['plural'] }}.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reservas->hasPages())
            <nav class="reservas-pagination" aria-label="Paginación de reservas">
                @if ($reservas->previousPageUrl())
                    <a href="{{ $reservas->previousPageUrl() }}">Anterior</a>
                @endif

                <span>
                    Página {{ $reservas->currentPage() }}
                    de {{ $reservas->lastPage() }}
                </span>

                @if ($reservas->nextPageUrl())
                    <a href="{{ $reservas->nextPageUrl() }}">Siguiente</a>
                @endif
            </nav>
        @endif
    </section>

</main>

@endsection
