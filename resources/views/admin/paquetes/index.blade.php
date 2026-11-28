
@extends('admin')

@section('title', 'Gestión de paquetes turísticos')
@section('active', 'paquetes')

@push('styles')
<style>
    /* CONTENEDOR PRINCIPAL */
    .wt-admin {
        max-width: 1200px;
        margin: 0 auto;
        padding: 35px 25px 70px;
        color: var(--ink, #16354a);
    }

    /* ENCABEZADO */
    .wt-admin__top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .wt-admin__label {
        color: #1377ae;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .wt-admin h1 {
        font-size: clamp(26px, 4vw, 36px);
        margin: 8px 0;
        font-weight: 700;
    }

    .wt-admin__desc {
        color: var(--slate, #64798a);
        margin: 0;
        line-height: 1.6;
    }

    /* BOTÓN NUEVO PAQUETE */
    .wt-admin__button {
        display: inline-block;
        background: #106d9f;
        color: white !important;
        text-decoration: none;
        padding: 13px 20px;
        border-radius: 10px;
        font-weight: 700;
        white-space: nowrap;
        transition: background .2s ease;
    }

    .wt-admin__button:hover {
        background: #084e74;
    }

    /* TARJETAS DE RESUMEN */
    .paquete-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .paquete-stat {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 4px 15px rgba(20, 50, 75, .03);
    }

    .paquete-stat__label {
        font-size: 12px;
        font-weight: 700;
        color: #64798a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .paquete-stat__value {
        font-size: 26px;
        font-weight: 800;
        color: #102331;
        margin-top: 4px;
    }

    /* BUSCADOR Y FILTROS */
    .paquete-filter-card {
        background: #fff;
        border: 1px solid #e0e9ef;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(20, 50, 75, .03);
    }

    .paquete-filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .paquete-filter-group {
        flex: 1;
        min-width: 180px;
    }

    .paquete-filter-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
        color: #475569;
    }

    .paquete-input,
    .paquete-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cfdce5;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .paquete-btn-filter {
        background: #106d9f;
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: background .2s ease;
    }

    .paquete-btn-filter:hover {
        background: #084e74;
    }

    .paquete-btn-clean {
        background: transparent;
        color: #64798a;
        border: 1px solid #cfdce5;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    /* TARJETA DE TABLA */
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
        min-width: 780px;
    }

    .wt-admin__table th {
        background: rgba(128, 155, 172, .09);
        padding: 17px;
        text-align: left;
        font-size: 11px;
        color: var(--slate, #536e80);
        text-transform: uppercase;
        white-space: nowrap;
    }

    .wt-admin__table td {
        padding: 17px;
        border-top: 1px solid #e8eef2;
        vertical-align: middle;
        font-size: 13px;
    }

    /* INFORMACIÓN DEL PAQUETE */
    .wt-admin__package {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .wt-admin__image {
        width: 72px;
        height: 55px;
        border-radius: 9px;
        object-fit: cover;
        background: #e8f0f6;
        flex-shrink: 0;
    }

    .wt-admin__name {
        font-weight: 700;
        font-size: 14px;
    }

    .wt-admin__sub {
        color: var(--slate, #728595);
        font-size: 12px;
        margin-top: 5px;
    }

    .wt-admin__price {
        font-weight: 800;
        white-space: nowrap;
    }

    /* ESTADOS */
    .wt-admin__status {
        display: inline-block;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .wt-admin__status--on {
        background: #e5f7ed;
        color: #177344;
    }

    .wt-admin__status--off {
        background: #f2f2f2;
        color: #697782;
    }

    /* BOTONES EDITAR Y ELIMINAR */
    .wt-admin__actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: nowrap;
    }

    .wt-admin__edit {
        color: #106d9f;
        font-weight: 700;
        text-decoration: none;
        padding: 8px 4px;
    }

    .wt-admin__edit:hover {
        text-decoration: underline;
    }

    .wt-admin__delete-form {
        margin: 0;
    }

    .wt-admin__delete {
        background: #fff1f0;
        color: #b42318;
        border: 1px solid #f3c5c0;
        border-radius: 8px;
        padding: 9px 13px;
        cursor: pointer;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        transition: background .2s ease;
    }

    .wt-admin__delete:hover {
        background: #ffe0dc;
    }

    .wt-admin__delete:focus-visible {
        outline: 2px solid #b42318;
        outline-offset: 2px;
    }

    /* MENSAJES */
    .wt-admin__alert {
        background: #e5f7ed;
        color: #17683f;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #b9e3c7;
    }

    .wt-admin__alert--error {
        background: #fff1f0;
        color: #b42318;
        border: 1px solid #f3c5c0;
    }

    /* TABLA VACÍA */
    .wt-admin__empty {
        padding: 45px 20px;
        text-align: center;
        color: var(--slate, #64798a);
    }

    /* PAGINACIÓN */
    .wt-admin__pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin-top: 25px;
    }

    .wt-admin__pagination a {
        color: #106d9f;
        font-weight: 700;
        text-decoration: none;
    }

    .wt-admin__pagination span {
        color: var(--slate, #60798a);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .wt-admin {
            padding: 25px 15px 50px;
        }

        .wt-admin__top {
            align-items: flex-start;
        }

        .wt-admin__button {
            padding: 11px 16px;
        }
    }
</style>
@endpush

@section('content')

<main class="wt-admin">

    <!-- ENCABEZADO ADMINISTRATIVO -->
    <div class="wt-admin__top">

        <div>

            <div class="wt-admin__label">
                Panel de administración
            </div>

            <h1>Gestión de paquetes turísticos</h1>

            <p class="wt-admin__desc">
                Administra los destinos, precios y servicios
                disponibles en World Travel.
            </p>

        </div>

        <!-- CREATE: NUEVO PAQUETE -->
        <a
            href="{{ route('admin.paquetes.create') }}"
            class="wt-admin__button"
        >
            + Nuevo paquete
        </a>

    </div>

    <!-- MENSAJE DE ÉXITO -->
    @if (session('success'))

        <div class="wt-admin__alert" role="status">
            {{ session('success') }}
        </div>

    @endif

    <!-- MENSAJE DE ERROR -->
    @if (session('error'))

        <div
            class="wt-admin__alert wt-admin__alert--error"
            role="alert"
        >
            {{ session('error') }}
        </div>

    @endif

    <!-- TARJETAS DE RESUMEN -->
    <section class="paquete-stats" aria-label="Resumen de paquetes turísticos">

        <div class="paquete-stat">
            <div class="paquete-stat__label">Total paquetes</div>
            <div class="paquete-stat__value">{{ $totalPaquetes }}</div>
        </div>

        <div class="paquete-stat">
            <div class="paquete-stat__label">Activos</div>
            <div class="paquete-stat__value">{{ $paquetesActivos }}</div>
        </div>

        <div class="paquete-stat">
            <div class="paquete-stat__label">Inactivos</div>
            <div class="paquete-stat__value">{{ $paquetesInactivos }}</div>
        </div>

        <div class="paquete-stat">
            <div class="paquete-stat__label">Destacados</div>
            <div class="paquete-stat__value">{{ $paquetesDestacados }}</div>
        </div>

    </section>

    <!-- BUSCADOR Y FILTROS -->
    <section class="paquete-filter-card">
        <form
            class="paquete-filter-form"
            method="GET"
            action="{{ route('admin.paquetes.index') }}"
        >

            <div class="paquete-filter-group" style="flex: 2;">
                <label class="paquete-filter-label" for="q">
                    Buscar paquete o destino
                </label>
                <input
                    class="paquete-input"
                    type="search"
                    id="q"
                    name="q"
                    value="{{ $busqueda }}"
                    placeholder="Título, destino, país o descripción..."
                >
            </div>

            <div class="paquete-filter-group">
                <label class="paquete-filter-label" for="estado">
                    Estado
                </label>
                <select class="paquete-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="activo" @selected($estado === 'activo')>
                        Activos
                    </option>
                    <option value="inactivo" @selected($estado === 'inactivo')>
                        Inactivos
                    </option>
                </select>
            </div>

            <div class="paquete-filter-group">
                <label class="paquete-filter-label" for="destacado">
                    Destacado
                </label>
                <select class="paquete-select" id="destacado" name="destacado">
                    <option value="">Todos</option>
                    <option value="1" @selected(in_array($destacado, ['1', 'si', 'true', 'destacado'], true))>
                        Solo destacados
                    </option>
                    <option value="0" @selected(in_array($destacado, ['0', 'no', 'false', 'no_destacado'], true))>
                        Sin destacar
                    </option>
                </select>
            </div>

            <div>
                <button type="submit" class="paquete-btn-filter">
                    Buscar
                </button>

                @if ($busqueda !== '' || $estado !== '' || $destacado !== '')
                    <a
                        href="{{ route('admin.paquetes.index') }}"
                        class="paquete-btn-clean"
                    >
                        Limpiar
                    </a>
                @endif
            </div>

        </form>
    </section>

    <!-- LISTADO DE PAQUETES -->
    <div class="wt-admin__card">

        <table class="wt-admin__table">

            <thead>
                <tr>
                    <th>Paquete turístico</th>
                    <th>Duración</th>
                    <th>Precio USD</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($paquetes as $paquete)

                    <tr>

                        <!-- INFORMACIÓN DEL PAQUETE -->
                        <td>

                            <div class="wt-admin__package">

                                @if ($paquete->imagen_url)

                                    <img
                                        src="{{ $paquete->imagen_url }}"
                                        alt="{{ $paquete->destino }}"
                                        class="wt-admin__image"
                                    >

                                @endif

                                <div>

                                    <div class="wt-admin__name">
                                        {{ $paquete->titulo }}
                                    </div>

                                    <div class="wt-admin__sub">
                                        {{ $paquete->destino }},
                                        {{ $paquete->pais }}
                                    </div>

                                </div>

                            </div>

                        </td>

                        <!-- DURACIÓN -->
                        <td>
                            {{ $paquete->duracion_dias }} días
                        </td>

                        <!-- PRECIO -->
                        <td class="wt-admin__price">

                            US$ {{ number_format(
                                (float) $paquete->precio,
                                2,
                                '.',
                                ','
                            ) }}

                        </td>

                        <!-- ESTADO -->
                        <td>

                            @if ($paquete->activo)

                                <span
                                    class="wt-admin__status
                                           wt-admin__status--on"
                                >
                                    Activo
                                </span>

                            @else

                                <span
                                    class="wt-admin__status
                                           wt-admin__status--off"
                                >
                                    Inactivo
                                </span>

                            @endif

                        </td>

                        <!-- ACCIONES -->
                        <td>

                            <div class="wt-admin__actions">

                                <!-- UPDATE: EDITAR -->
                                <a
                                    class="wt-admin__edit"
                                    href="{{ route('admin.paquetes.edit', $paquete) }}"
                                >
                                    Editar
                                </a>

                                <!-- DELETE: ELIMINAR -->
                                <form
                                    class="wt-admin__delete-form"
                                    action="{{ route('admin.paquetes.destroy', $paquete) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este paquete turístico? Esta acción no se puede deshacer.');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="wt-admin__delete"
                                        aria-label="Eliminar paquete {{ $paquete->titulo }}"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <!-- SIN REGISTROS -->
                    <tr>

                        <td colspan="5">

                            <div class="wt-admin__empty">

                                @if ($busqueda !== '' || $estado !== '' || $destacado !== '')
                                    No se encontraron paquetes con los filtros seleccionados.
                                @else
                                    Todavía no hay paquetes registrados.

                                    Utiliza «Nuevo paquete» para empezar.
                                @endif

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINACIÓN -->
    @if ($paquetes->hasPages())

        <nav
            class="wt-admin__pagination"
            aria-label="Paginación de paquetes"
        >

            @if ($paquetes->previousPageUrl())

                <a href="{{ $paquetes->previousPageUrl() }}">
                    Anterior
                </a>

            @endif

            <span>
                Página {{ $paquetes->currentPage() }}
                de {{ $paquetes->lastPage() }}
            </span>

            @if ($paquetes->nextPageUrl())

                <a href="{{ $paquetes->nextPageUrl() }}">
                    Siguiente
                </a>

            @endif

        </nav>

    @endif

</main>

@endsection
