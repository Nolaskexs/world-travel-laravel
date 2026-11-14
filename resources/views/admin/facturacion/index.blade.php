@extends('admin')

@section('title', 'Facturación y Comprobantes')
@section('active', 'facturacion')

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
        font-size: clamp(24px, 3.5vw, 32px);
        margin: 6px 0;
        font-weight: 700;
    }

    .wt-admin__desc {
        color: var(--slate, #64798a);
        margin: 0;
        line-height: 1.5;
    }

    .wt-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .wt-stat-card {
        background: var(--surface, #fff);
        border: 1px solid #e0e9ef;
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .wt-stat-card--green .wt-stat-card__val { color: #059669; }
    .wt-stat-card--amber .wt-stat-card__val { color: #d97706; }
    .wt-stat-card--red .wt-stat-card__val { color: #dc2626; }
    .wt-stat-card--blue .wt-stat-card__val { color: #106d9f; }

    .wt-stat-card__val {
        font-size: 26px;
        font-weight: 700;
    }

    .wt-stat-card__lbl {
        font-size: 13px;
        color: var(--slate, #64798a);
    }

    .wt-filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
    }

    .wt-search-input {
        flex: 1;
        min-width: 250px;
        padding: 10px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
    }

    .wt-select {
        padding: 10px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
    }

    .wt-btn-filter {
        background: #106d9f;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        cursor: pointer;
    }

    .wt-admin__card {
        background: var(--surface, #ffffff);
        border: 1px solid #e0e9ef;
        border-radius: 15px;
        overflow-x: auto;
        box-shadow: 0 8px 30px rgba(20, 50, 75, .05);
    }

    .wt-admin__table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .wt-admin__table th {
        background: rgba(128, 155, 172, .09);
        padding: 14px 18px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        color: var(--slate, #64798a);
        font-weight: 700;
    }

    .wt-admin__table td {
        padding: 14px 18px;
        border-top: 1px solid #eef3f6;
        vertical-align: middle;
        font-size: 14px;
    }

    .wt-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .wt-badge--paid {
        background: #d1fae5;
        color: #065f46;
    }

    .wt-badge--pending {
        background: #fef3c7;
        color: #92400e;
    }

    .wt-badge--canceled {
        background: #fee2e2;
        color: #991b1b;
    }
</style>
@endpush

@section('content')
<main class="wt-admin">

    <div class="wt-admin__top">
        <div>
            <span class="wt-admin__label">Panel Administrativo</span>
            <h1>Facturación y Finanzas</h1>
            <p class="wt-admin__desc">Consulta ingresos, pagos cobrados, pendientes y comprobantes electrónicos emitidos.</p>
        </div>
    </div>

    <section class="wt-stats-grid">
        <div class="wt-stat-card wt-stat-card--green">
            <span class="wt-stat-card__val">US$ {{ number_format($totalCobrado, 2) }}</span>
            <span class="wt-stat-card__lbl">Ingresos Cobrados</span>
        </div>
        <div class="wt-stat-card wt-stat-card--amber">
            <span class="wt-stat-card__val">US$ {{ number_format($totalPendiente, 2) }}</span>
            <span class="wt-stat-card__lbl">Pendiente de Cobro</span>
        </div>
        <div class="wt-stat-card wt-stat-card--red">
            <span class="wt-stat-card__val">US$ {{ number_format($totalAnulado, 2) }}</span>
            <span class="wt-stat-card__lbl">Anulaciones / Reembolsos</span>
        </div>
        <div class="wt-stat-card wt-stat-card--blue">
            <span class="wt-stat-card__val">{{ $totalOperaciones }}</span>
            <span class="wt-stat-card__lbl">Comprobantes Emitidos</span>
        </div>
    </section>

    <form class="wt-filter-bar" method="GET" action="{{ route('admin.facturacion.index') }}">
        <input class="wt-search-input" type="search" name="q" value="{{ $busqueda }}" placeholder="Buscar por N° comprobante, cliente o paquete...">

        <select class="wt-select" name="estado">
            <option value="">Todos los estados</option>
            <option value="confirmada" {{ $estado === 'confirmada' ? 'selected' : '' }}>Cobrado / Pagado</option>
            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente de pago</option>
            <option value="cancelada" {{ $estado === 'cancelada' ? 'selected' : '' }}>Anulado</option>
        </select>

        <button class="wt-btn-filter" type="submit">Filtrar</button>

        @if ($busqueda || $estado)
            <a href="{{ route('admin.facturacion.index') }}" style="color: #64798a; text-decoration: none; font-size: 13px; font-weight: 600;">Limpiar</a>
        @endif
    </form>

    <div class="wt-admin__card">
        <table class="wt-admin__table">
            <thead>
                <tr>
                    <th>N° Comprobante</th>
                    <th>Fecha de Emisión</th>
                    <th>Cliente</th>
                    <th>Concepto / Paquete</th>
                    <th>Monto Total</th>
                    <th>Estado de Pago</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($comprobantes as $item)
                    @php
                        $numeroBoleta = 'B001-' . str_pad($item->id, 6, '0', STR_PAD_LEFT);
                        $fechaEmision = \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i');
                    @endphp
                    <tr>
                        <td>
                            <strong style="color: #106d9f;">{{ $numeroBoleta }}</strong>
                        </td>
                        <td style="color: #64798a; font-size: 13px;">
                            {{ $fechaEmision }}
                        </td>
                        <td>
                            <div>
                                <strong>{{ $item->cliente_nombre }}</strong>
                                <div style="font-size: 12px; color: #64798a;">{{ $item->cliente_email }}</div>
                            </div>
                        </td>
                        <td>
                            {{ $item->paquete_titulo }}
                        </td>
                        <td>
                            <strong>US$ {{ number_format($item->total, 2) }}</strong>
                        </td>
                        <td>
                            @if ($item->estado === 'confirmada')
                                <span class="wt-badge wt-badge--paid">Pagado</span>
                            @elseif ($item->estado === 'pendiente')
                                <span class="wt-badge wt-badge--pending">Pendiente</span>
                            @else
                                <span class="wt-badge wt-badge--canceled">Anulado</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.reservas.show', $item->id) }}" style="color: #106d9f; text-decoration: none; font-weight: 600; font-size: 13px;">
                                Ver Reserva →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 35px; color: #64798a;">
                            No se encontraron comprobantes ni transacciones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $comprobantes->links() }}
    </div>

</main>
@endsection
