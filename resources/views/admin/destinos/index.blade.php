@extends('admin')

@section('title', 'Gestión de destinos')
@section('active', 'destinos')

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

    .wt-admin__button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #106d9f;
        color: white !important;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 9px;
        font-weight: 700;
        font-size: 14px;
        transition: background .2s ease;
    }

    .wt-admin__button:hover {
        background: #084e74;
    }

    .wt-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

    .wt-stat-card__val {
        font-size: 26px;
        font-weight: 700;
        color: #106d9f;
    }

    .wt-stat-card__lbl {
        font-size: 13px;
        color: var(--slate, #64798a);
    }

    .wt-search-box {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .wt-search-box input {
        flex: 1;
        max-width: 400px;
        padding: 10px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
    }

    .wt-search-box button {
        background: #106d9f;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0 16px;
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
        min-width: 700px;
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
        background: #e6f3fa;
        color: #0d5f8c;
    }

    .wt-actions {
        display: flex;
        gap: 8px;
    }

    .wt-btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid #cfdce5;
        color: var(--ink, #16354a);
        background: #fff;
        cursor: pointer;
    }

    .wt-btn-sm:hover {
        background: #f1f5f8;
    }

    .wt-btn-danger {
        color: #b91c1c;
        border-color: #fecaca;
        background: #fff;
    }

    .wt-btn-danger:hover {
        background: #fee2e2;
    }

    .wt-alert {
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .wt-alert--success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .wt-alert--error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
</style>
@endpush

@section('content')
<main class="wt-admin">

    <div class="wt-admin__top">
        <div>
            <span class="wt-admin__label">Panel Administrativo</span>
            <h1>Destinos Turísticos</h1>
            <p class="wt-admin__desc">Administra los destinos internacionales y nacionales disponibles para World Travel.</p>
        </div>

        <a href="{{ route('admin.destinos.create') }}" class="wt-admin__button">
            + Nuevo Destino
        </a>
    </div>

    @if (session('success'))
        <div class="wt-alert wt-alert--success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="wt-alert wt-alert--error">
            <strong>Protección de Integridad Referencial:</strong> {{ session('error') }}
        </div>
    @endif

    <section class="wt-stats-grid">
        <div class="wt-stat-card">
            <span class="wt-stat-card__val">{{ $totalDestinos }}</span>
            <span class="wt-stat-card__lbl">Destinos Registrados</span>
        </div>
        <div class="wt-stat-card">
            <span class="wt-stat-card__val">{{ $totalPaises }}</span>
            <span class="wt-stat-card__lbl">Países Cubiertos</span>
        </div>
    </section>

    <form class="wt-search-box" method="GET" action="{{ route('admin.destinos.index') }}">
        <input type="search" name="q" value="{{ $busqueda }}" placeholder="Buscar por ciudad, país o descripción...">
        <button type="submit">Buscar</button>
        @if ($busqueda)
            <a href="{{ route('admin.destinos.index') }}" class="wt-btn-sm" style="align-self: center;">Limpiar</a>
        @endif
    </form>

    <div class="wt-admin__card">
        <table class="wt-admin__table">
            <thead>
                <tr>
                    <th>Destino</th>
                    <th>País</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($destinos as $destino)
                    <tr>
                        <td>
                            <strong>{{ $destino->nombre }}</strong>
                        </td>
                        <td>
                            <span class="wt-badge">{{ $destino->pais }}</span>
                        </td>
                        <td style="max-width: 380px; color: #536b7b;">
                            {{ Str::limit($destino->descripcion ?: 'Sin descripción registrada.', 90) }}
                        </td>
                        <td>
                            <div class="wt-actions">
                                <a href="{{ route('admin.destinos.edit', $destino) }}" class="wt-btn-sm">Editar</a>
                                <form method="POST" action="{{ route('admin.destinos.destroy', $destino) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este destino?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="wt-btn-sm wt-btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 35px; color: #64798a;">
                            No se encontraron destinos turísticos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $destinos->links() }}
    </div>

</main>
@endsection
