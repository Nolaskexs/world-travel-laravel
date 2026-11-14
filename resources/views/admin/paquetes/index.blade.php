
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

                                Todavía no hay paquetes registrados.

                                Utiliza «Nuevo paquete» para empezar.

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
